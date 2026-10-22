"""
The fuzzing loop.

    producer thread ──batches──▶ queue ──▶ runner threads (run-tests.php)
                                                │
                                   triage ◀─────┘──▶ bug store, stats, UI

The producer fuses and stages batches ahead of time. Several runners each
drive their own run-tests.php instance, so the slow tail of one batch (a
test running into its timeout) overlaps with the start of the next instead
of idling the CPUs.
"""

import os
import platform
import queue
import random
import re
import subprocess
import sys
import threading
import time

from .config import Config
from .corpus import SeedPool, load_seeds, without_missing_extensions
from .fusion import Fuser
from .generator import TestGenerator
from .harness import Harness, loaded_extensions, remove_tree
from .knowledge import Knowledge
from .mutator import Mutator
from .stats import Stats
from .triage import BugStore, classify
from .ui import Dashboard, PlainReporter, final_report


class FuzzError(RuntimeError):
    pass


def _describe_target(php: str) -> str:
    try:
        out = subprocess.run([php, "-n", "-v"], capture_output=True, text=True, timeout=60,
                             env={**os.environ, "ASAN_OPTIONS": "detect_leaks=0"}).stdout
        first = out.splitlines()[0] if out else "?"
        return re.sub(r'\s*\(built:[^)]*\)', '', first)
    except Exception:
        return "?"


class Fuzzer:
    def __init__(self, cfg: Config):
        self.cfg = cfg.finalize()
        self.rng = random.Random(cfg.rng_seed)
        self.stats = Stats()
        self.stop_event = threading.Event()
        self.queue: "queue.Queue" = queue.Queue(maxsize=self.cfg.runners + 1)
        self._next_bid = 0
        self._bid_lock = threading.Lock()
        self.threads = []

    # -- setup ------------------------------------------------------------

    def check(self):
        cfg = self.cfg
        if not cfg.php_bin.exists():
            raise FuzzError(f"PHP binary not found: {cfg.php_bin} (run ./prepare.sh first)")
        if not cfg.run_tests.exists():
            raise FuzzError(f"run-tests.php not found: {cfg.run_tests}")
        if not cfg.seeds_db.exists():
            raise FuzzError(f"seed database not found: {cfg.seeds_db} (run ./prepare.sh first)")

    def setup(self):
        self.check()
        cfg = self.cfg
        seeds = load_seeds(str(cfg.seeds_db))
        if not seeds:
            raise FuzzError(f"no seeds in {cfg.seeds_db}")
        seeds, dropped = without_missing_extensions(seeds, loaded_extensions(str(cfg.php_bin)))
        self.pool = SeedPool(seeds, rng=self.rng, translated_weight=cfg.translated_weight)
        knowledge = Knowledge(str(cfg.apis_db), str(cfg.class_db), rng=self.rng)
        mutator = Mutator(rng=self.rng) if cfg.mutation else None
        fuser = Fuser(list(cfg.strategies), rng=self.rng, mutator=mutator,
                      chain_rate=cfg.chain_rate, weights=cfg.strategy_weights)
        self.generator = TestGenerator(cfg, self.pool, fuser, knowledge, rng=self.rng)
        self.harness = Harness(cfg)
        self.bugs = BugStore(str(cfg.bugs_dir), max_dups=cfg.max_dups)
        self.run_dir = os.path.join(str(cfg.work_dir), f"run-{os.getpid()}")
        os.makedirs(self.run_dir, exist_ok=True)
        os.makedirs(cfg.log_dir, exist_ok=True)

        origins = {}
        for s in seeds:
            origins[s.origin] = origins.get(s.origin, 0) + 1
        info = self.stats.info
        info["root"] = str(cfg.root)
        info["bugs_dir"] = os.path.relpath(str(cfg.bugs_dir), str(cfg.root))
        info["target"] = f"{_describe_target(str(cfg.php_bin))}  {cfg.php_bin}"
        info["seeds"] = f"{len(seeds):,}  (" + ", ".join(f"{n:,} {o}" for o, n in sorted(origins.items())) + ")" + \
            f"   apis {len(knowledge.functions):,}  classes {len(knowledge.classes):,}"
        info["harness"] = (f"run-tests.php via {self.harness.describe()} · {cfg.runners} runners × -j{cfg.jobs_per_runner}"
                           f" · batch {cfg.batch_size} · timeout {cfg.timeout}s · Zend MM batches {cfg.zendmm_ratio:.0%}")
        info["fusion"] = (", ".join(cfg.strategies) + f" · chain {cfg.chain_rate:.0%}"
                          + f" · mutation {'on' if cfg.mutation else 'off'}"
                          + f" · apifuzz {cfg.apifuzz_rate if cfg.apifuzz else 0:.0%}"
                          + f" · classfuzz {cfg.classfuzz_rate if cfg.classfuzz else 0:.0%}"
                          + f" · jit {cfg.jit_rate if cfg.ini else 0:.0%}")
        info["unique_bugs"] = self.bugs.unique()
        self.stats.event(f"loaded {len(seeds):,} seeds ({dropped:,} dropped: extension not in this build); "
                         f"{self.bugs.unique()} bug signatures known from earlier runs")

    # -- threads ----------------------------------------------------------

    def _producer(self):
        while not self.stop_event.is_set():
            with self._bid_lock:
                self._next_bid += 1
                bid = self._next_bid
            try:
                batch = self.generator.make_batch(bid, self.cfg.batch_size, self.run_dir)
            except Exception as e:
                self.stats.event(f"generator error: {type(e).__name__}: {e}")
                time.sleep(1)
                continue
            while not self.stop_event.is_set():
                try:
                    self.queue.put(batch, timeout=0.5)
                    break
                except queue.Full:
                    continue
            else:
                remove_tree(batch.dir)

    def _runner(self, idx: int):
        jobs = self.cfg.jobs_per_runner
        while not self.stop_event.is_set():
            try:
                batch = self.queue.get(timeout=0.5)
            except queue.Empty:
                continue
            if not batch.tests:
                remove_tree(batch.dir)
                continue
            self.stats.batch_started(batch.bid, len(batch.tests), batch.asan)
            try:
                results = self.harness.run(batch, jobs)
                if self.stop_event.is_set():
                    # interrupted: the results are incomplete, do not count them
                    self.stats.batch_done(batch.bid, [])
                    continue
                self._triage(batch, results)
            except Exception as e:
                self.stats.event(f"runner {idx} error: {type(e).__name__}: {e}")
                self.stats.batch_done(batch.bid, [])
            finally:
                if not self.cfg.keep_batches:
                    remove_tree(batch.dir)

    def _triage(self, batch, results):
        outcomes = []
        for r in results:
            oc = classify(r.output, r.status, leaks=self.cfg.leaks)
            outcomes.append((r.test, oc))
            if oc.kind in ("parse", "ok", "fatal", "bug", "timeout"):
                self.pool.feedback(r.test.parents, parsed=oc.kind != "parse")
            if oc.kind == "bug":
                is_new, bug_dir = self.bugs.record(r, oc, {
                    "zend_mm": not batch.asan, "batch": batch.bid})
                self.stats.bug_found(is_new, oc.signature, r.test.strategy, bug_dir)
                if is_new:
                    self.stats.event(f"NEW BUG {oc.signature}  [{r.test.strategy}]  -> {bug_dir}")
        self.stats.info["unique_bugs"] = self.bugs.unique()
        self.stats.batch_done(batch.bid, outcomes)
        lost = sum(1 for _, oc in outcomes if oc.kind == "lost")
        if lost > len(outcomes) // 2 and outcomes:
            self.stats.event(f"batch {batch.bid}: {lost}/{len(outcomes)} tests produced no result "
                             f"(see {batch.dir}/harness.log with --keep-batches)")
        gen_errors = getattr(self.generator, "errors", 0)
        if gen_errors and gen_errors != getattr(self, "_reported_gen_errors", 0):
            self._reported_gen_errors = gen_errors
            self.stats.event(f"{gen_errors} fusion errors so far; last: {self.generator.last_error}")

    # -- main loop --------------------------------------------------------

    def _done(self) -> bool:
        snap_tests = self.stats.tests
        if self.cfg.max_tests and snap_tests >= self.cfg.max_tests:
            return True
        if self.cfg.time_limit and time.time() - self.stats.start >= self.cfg.time_limit:
            return True
        return False

    def run(self) -> int:
        self.setup()
        ui_mode = self.cfg.ui
        if ui_mode == "auto":
            ui_mode = "dash" if sys.stdout.isatty() and os.environ.get("TERM") not in (None, "dumb") else "plain"
        ui = Dashboard(self.stats) if ui_mode == "dash" else PlainReporter(self.stats)
        self.stats.event(f"fuzzing started (pid {os.getpid()}, python {platform.python_version()})")
        self.threads = [threading.Thread(target=self._producer, daemon=True, name="producer")]
        self.threads += [threading.Thread(target=self._runner, args=(i,), daemon=True, name=f"runner{i}")
                         for i in range(self.cfg.runners)]
        for t in self.threads:
            t.start()
        ui.start()
        stats_path = os.path.join(str(self.cfg.log_dir), "stats.json")
        interrupted = False
        try:
            last_dump = 0.0
            while not self._done():
                time.sleep(0.5)
                if time.time() - last_dump > 10:
                    self.stats.dump(stats_path, {"unique_bugs": self.bugs.unique(),
                                                 "penalised_seeds": self.pool.penalised()})
                    last_dump = time.time()
        except KeyboardInterrupt:
            interrupted = True
        finally:
            self.stats.info["stopping"] = True
            self.stop_event.set()
            self.harness.kill_all()
            for t in self.threads:
                t.join(timeout=30)
            ui.stop()
            self.stats.dump(stats_path, {"unique_bugs": self.bugs.unique(),
                                         "penalised_seeds": self.pool.penalised()})
            if not self.cfg.keep_batches:
                remove_tree(self.run_dir)
            print(final_report(self.stats.snapshot(), self.bugs.unique(), str(self.cfg.bugs_dir)))
        return 130 if interrupted else 0
