"""
Executing batches of fused tests with PHP's official test harness.

FlowFusion 1.x ran `make test` inside php-src under a 30 s `timeout`,
after sed-patching run-tests.php, deleting php-src's own tests, and later
`kill -9`-ing every php process it could grep and `git clean`-ing the tree.
This module calls run-tests.php directly instead:

- tests live in a scratch batch directory, never inside php-src; the test
  processes' cwd (TEST_PHP_SRCDIR) is a scratch directory too, so files a
  test writes are removed with the batch;
- run-tests.php is executed by a non-sanitized PHP when one is available.
  The ASan/debug build as the *harness* interpreter is slow, and its
  stream_select() fails with EINTR so the per-test timeout never fires
  (a `while(true){}` test then hangs the batch forever);
- a watchdog enforces a wall-clock limit per test (run-tests.php's own
  timeout only fires when a test stops printing);
- each batch runs in its own session; when it ends — normally or at its
  deadline — the whole process group is killed, so no orphan php survives;
- `--asan` sets USE_ZEND_ALLOC=0 so ASan sees every allocation instead of
  the Zend MM's large chunks (a share of batches keeps the Zend MM on: some
  bugs only show as heap corruption inside it);
- the per-test output diff, which nobody reads while fuzzing and which
  dominates the cost of tests with large outputs, is turned off;
- results come from the -W status file and each test's .out file.
"""

import math
import os
import shutil
import signal
import subprocess
import threading
import time
from dataclasses import dataclass
from typing import List, Optional

from .generator import Batch, GeneratedTest
from .knowledge import BLOCKED_FUNCTIONS

OUTPUT_HEAD = 192 * 1024      # bytes of a long .out kept for triage: its start...
OUTPUT_TAIL = 64 * 1024       # ...and its end, where a sanitizer report would be
WATCHDOG_MARK = "** killed by the FlowFusion watchdog (wall-clock timeout) **"


@dataclass
class TestResult:
    test: GeneratedTest
    status: str                   # FAILED | PASSED | SKIPPED | BORKED | LOST
    output: str                   # contents of the .out file (may be truncated)


def _php_version(php: str) -> Optional[int]:
    try:
        r = subprocess.run([php, "-n", "-r", "echo PHP_VERSION_ID;"], capture_output=True,
                           text=True, timeout=30, env={**os.environ, "ASAN_OPTIONS": "detect_leaks=0"})
        return int(r.stdout.strip()) if r.returncode == 0 else None
    except (OSError, ValueError, subprocess.TimeoutExpired):
        return None


def _is_sanitized(php: str) -> bool:
    try:
        r = subprocess.run(["nm", "-D", php], capture_output=True, text=True, timeout=30)
        return "__asan_init" in r.stdout
    except (OSError, subprocess.TimeoutExpired):
        return False


def loaded_extensions(php: str) -> Optional[set]:
    """Lower-cased names of the extensions compiled into `php` (as an
    --EXTENSIONS-- section names them), or None when it cannot be run."""
    try:
        r = subprocess.run([php, "-n", "-m"], capture_output=True, text=True, timeout=60,
                           env={**os.environ, "ASAN_OPTIONS": "detect_leaks=0"})
    except (OSError, subprocess.TimeoutExpired):
        return None
    if r.returncode != 0:
        return None
    names = {l.strip().lower() for l in r.stdout.splitlines() if l.strip() and not l.startswith("[")}
    if "zend opcache" in names:
        names.add("opcache")
    return names


def find_symbolizer() -> Optional[str]:
    for cand in [shutil.which("llvm-symbolizer")] + \
            [f"/usr/lib/llvm-{v}/bin/llvm-symbolizer" for v in range(20, 9, -1)] + \
            [shutil.which(f"llvm-symbolizer-{v}") for v in range(20, 9, -1)]:
        if cand and os.path.exists(cand):
            return cand
    return None


class Watchdog(threading.Thread):
    """Wall-clock limit for every test process.

    run-tests.php's --set-timeout is an *inactivity* timeout: it fires only
    when a test prints nothing for that long, so a test looping over an
    echo runs forever and holds its whole batch. Every second this scans
    /proc for processes of the target binary in a running batch's session
    and SIGKILLs those older than the limit; run-tests.php then records
    `Termsig=9` for the test, which triage counts as a timeout."""

    def __init__(self, php_bin: str, limit: float):
        super().__init__(daemon=True, name="watchdog")
        self.php = os.path.realpath(php_bin)
        self.limit = limit
        self.sessions = set()
        self.kills = 0
        self.killed = set()             # test .php paths of killed processes
        self._stop = threading.Event()
        self._hz = os.sysconf("SC_CLK_TCK")

    def stop(self):
        self._stop.set()

    def run(self):
        while not self._stop.wait(1.0):
            if self.sessions:
                try:
                    self._scan()
                except OSError:
                    pass

    def _scan(self):
        with open("/proc/uptime") as f:
            uptime = float(f.read().split()[0])
        sessions = set(self.sessions)
        for name in os.listdir("/proc"):
            if not name.isdigit():
                continue
            try:
                with open(f"/proc/{name}/stat") as f:
                    stat = f.read()
                rest = stat[stat.rfind(")") + 2:].split()
                if int(rest[3]) not in sessions:            # field 6: session id
                    continue
                age = uptime - int(rest[19]) / self._hz       # field 22: start time
                if age > self.limit and os.path.realpath(f"/proc/{name}/exe") == self.php:
                    with open(f"/proc/{name}/cmdline", "rb") as f:
                        args = f.read().decode("latin-1").split("\0")
                    os.kill(int(name), signal.SIGKILL)
                    self.kills += 1
                    self.killed.update(a for a in args if a.endswith(".php"))
            except (OSError, ValueError, IndexError):
                continue


class Harness:
    def __init__(self, cfg):
        self.cfg = cfg
        self.php = str(cfg.php_bin)
        self.run_tests = str(cfg.run_tests)
        self.runner = cfg.harness_php or self._pick_runner()
        self.symbolizer = find_symbolizer()
        self._procs = {}
        self.watchdog = None
        if os.path.isdir("/proc/self"):
            self.watchdog = Watchdog(self.php, cfg.timeout + 1)
            self.watchdog.start()

    def _pick_runner(self) -> str:
        """A plain (non-ASan) PHP >= 8.0 that can run run-tests.php, or the
        target binary itself as a fallback."""
        for cand in ("php", "php8.4", "php8.3", "php8.2", "php8.1"):
            path = shutil.which(cand)
            if not path or os.path.realpath(path) == os.path.realpath(self.php):
                continue
            ver = _php_version(path)
            if ver and ver >= 80000 and not _is_sanitized(path):
                r = subprocess.run([path, "-n", "-l", self.run_tests], capture_output=True,
                                   text=True, timeout=60)
                if r.returncode == 0:
                    return path
        return self.php

    def describe(self) -> str:
        if self.runner == self.php:
            return "target php (no plain php found)"
        return f"{self.runner} (non-sanitized)"

    def environment(self, batch: Batch) -> dict:
        env = {k: v for k, v in os.environ.items() if not k.startswith(("TEST_PHP", "ASAN", "UBSAN", "LSAN"))}
        asan = [
            f"detect_leaks={1 if self.cfg.leaks else 0}",
            "allocator_may_return_null=1",    # huge allocations: PHP reports OOM, not ASan
            "handle_abort=1",                 # assertion aborts get a stack trace
            "symbolize=1",
            "disable_coredump=1",
        ]
        env.update({
            "NO_INTERACTION": "1",
            "TEST_PHP_SRCDIR": os.path.join(batch.dir, "cwd"),
            "ASAN_OPTIONS": ":".join(asan),
            "UBSAN_OPTIONS": "print_stacktrace=1",
            "SKIP_ONLINE_TESTS": "1",
            # run-tests.php diffs every failing test's output against
            # --EXPECT-- in PHP (an LCS over all output lines): 13 s for one
            # test printing 2.8 MB. Fuzzing never reads the diff, and this
            # is the harness's own switch for an external diff tool.
            "TEST_PHP_DIFF_CMD": "true",
        })
        if self.symbolizer:
            env["ASAN_SYMBOLIZER_PATH"] = self.symbolizer
        return env

    def command(self, batch: Batch, jobs: int) -> List[str]:
        # run-tests.php triples the timeout under --asan; undo that so
        # --timeout means wall-clock seconds per test either way.
        per_test = max(1, math.ceil(self.cfg.timeout / (3 if batch.asan else 1)))
        cmd = [self.runner, "-n", self.run_tests, "-p", self.php,
               "-q", "--no-color", "--no-progress", f"-j{max(1, jobs)}",
               "--set-timeout", str(per_test),
               "-W", os.path.join(batch.dir, "status.txt"),
               "-d", "disable_functions=" + ",".join(BLOCKED_FUNCTIONS)]
        if batch.asan:
            cmd.append("--asan")
        return cmd + [t.phpt for t in batch.tests]

    def deadline(self, batch: Batch, jobs: int) -> float:
        waves = math.ceil(len(batch.tests) / max(1, jobs))
        return 60 + waves * self.cfg.timeout * 1.5

    def run(self, batch: Batch, jobs: int) -> List[TestResult]:
        os.makedirs(os.path.join(batch.dir, "cwd"), exist_ok=True)
        log = open(os.path.join(batch.dir, "harness.log"), "wb")
        proc = subprocess.Popen(self.command(batch, jobs), cwd=batch.dir, env=self.environment(batch),
                                stdin=subprocess.DEVNULL, stdout=log, stderr=subprocess.STDOUT,
                                start_new_session=True)
        self._procs[batch.bid] = proc
        if self.watchdog:
            self.watchdog.sessions.add(proc.pid)    # the new session's id is its leader's pid
        try:
            proc.wait(timeout=self.deadline(batch, jobs))
            batch_timed_out = False
        except subprocess.TimeoutExpired:
            batch_timed_out = True
        finally:
            self._kill(proc)
            self._procs.pop(batch.bid, None)
            if self.watchdog:
                self.watchdog.sessions.discard(proc.pid)
            log.close()
        results = self._collect(batch)
        if batch_timed_out:
            for r in results:
                if r.status == "LOST":
                    r.output = "** batch deadline exceeded **"
        return results

    @staticmethod
    def _kill(proc):
        try:
            os.killpg(proc.pid, signal.SIGKILL)
        except (ProcessLookupError, PermissionError):
            pass
        try:
            proc.wait(timeout=10)
        except subprocess.TimeoutExpired:
            pass

    def kill_all(self):
        for proc in list(self._procs.values()):
            self._kill(proc)

    def _collect(self, batch: Batch) -> List[TestResult]:
        status = {}
        try:
            with open(os.path.join(batch.dir, "status.txt"), encoding="utf-8", errors="replace") as f:
                for line in f:
                    st, _, path = line.rstrip("\n").partition("\t")
                    if path:
                        status[os.path.normpath(path)] = st
        except OSError:
            pass
        results = []
        for t in batch.tests:
            st = status.get(os.path.normpath(t.phpt))
            out = ""
            out_path = t.phpt[:-5] + ".out"
            if os.path.exists(out_path):
                out = _read_bounded(out_path)
                st = st or "FAILED"
            if self.watchdog and t.phpt[:-5] + ".php" in self.watchdog.killed:
                self.watchdog.killed.discard(t.phpt[:-5] + ".php")
                out += "\n" + WATCHDOG_MARK + "\n"
            results.append(TestResult(t, st or "LOST", out))
        return results


def _read_bounded(path: str) -> str:
    size = os.path.getsize(path)
    with open(path, "rb") as f:
        if size <= OUTPUT_HEAD + OUTPUT_TAIL:
            data = f.read()
        else:
            data = f.read(OUTPUT_HEAD) + b"\n[... output truncated ...]\n"
            f.seek(size - OUTPUT_TAIL)
            data += f.read()
    return data.decode("latin-1")


def remove_tree(path: str):
    """rmtree that survives tests which chmod'ed their own files/dirs."""
    def onerror(func, p, _exc):
        try:
            os.chmod(os.path.dirname(p), 0o755)
            os.chmod(p, 0o755)
            func(p)
        except OSError:
            pass
    shutil.rmtree(path, onerror=onerror)
