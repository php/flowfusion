#!/usr/bin/env python3
"""
FlowFusion — fuzz the PHP interpreter by fusing its own test programs.

    python3 main.py                                  # fuzz with all strategies
    python3 main.py --strategies state --time-limit 2h
    python3 main.py --generate 20 --out /tmp/fused   # only write fused tests

Run ./prepare.sh once before fuzzing (php-src build, seeds, knowledge).
"""

import argparse
import os
import random
import re
import sys

from flowfusion.config import Config
from flowfusion.fusion import STRATEGIES


def duration(text: str) -> int:
    m = re.fullmatch(r'(\d+)\s*([smhd]?)', text.strip())
    if not m:
        raise argparse.ArgumentTypeError(f"bad duration {text!r} (use e.g. 90, 30m, 2h, 1d)")
    return int(m.group(1)) * {'': 1, 's': 1, 'm': 60, 'h': 3600, 'd': 86400}[m.group(2)]


def strategy_list(text: str):
    names = [n.strip() for n in text.split(',') if n.strip()]
    bad = [n for n in names if n not in STRATEGIES]
    if bad or not names:
        raise argparse.ArgumentTypeError(f"unknown strategy {bad or text!r}; choose from {', '.join(STRATEGIES)}")
    return tuple(dict.fromkeys(names))


def build_parser() -> argparse.ArgumentParser:
    d = Config()
    p = argparse.ArgumentParser(description="FlowFusion: fusion-based fuzzer for the PHP interpreter",
                                formatter_class=argparse.ArgumentDefaultsHelpFormatter)
    g = p.add_argument_group("fusion")
    g.add_argument("--strategies", type=strategy_list, default=d.strategies,
                   help="comma-separated fusion strategies: " + ", ".join(STRATEGIES))
    g.add_argument("--weights", default="",
                   help="relative strategy weights, e.g. dataflow=2,state=1")
    g.add_argument("--chain-rate", type=float, default=d.chain_rate,
                   help="probability of fusing the child with a third seed")
    g.add_argument("--no-mutation", action="store_true", help="do not mutate seeds")
    g.add_argument("--no-apifuzz", action="store_true", help="do not append internal-function calls")
    g.add_argument("--apifuzz-rate", type=float, default=d.apifuzz_rate)
    g.add_argument("--no-classfuzz", action="store_true", help="do not instantiate internal classes")
    g.add_argument("--classfuzz-rate", type=float, default=d.classfuzz_rate)
    g.add_argument("--no-ini", action="store_true", help="do not fuzz INI settings / JIT modes")
    g.add_argument("--jit-rate", type=float, default=d.jit_rate, help="share of tests run with the JIT")
    g.add_argument("--translated-weight", type=float, default=d.translated_weight,
                   help="selection weight of translated seeds relative to php-src seeds")

    g = p.add_argument_group("execution")
    g.add_argument("-j", "--jobs", type=int, default=d.jobs, help="parallel test processes (default: 1.5 x CPUs)")
    g.add_argument("--runners", type=int, default=0,
                   help="concurrent run-tests.php instances, jobs are split between them (default: jobs / 3)")
    g.add_argument("--batch-size", type=int, default=0, help="tests per batch (default: 24 x jobs per runner)")
    g.add_argument("--timeout", type=int, default=d.timeout, help="seconds per test")
    g.add_argument("--zendmm-ratio", type=float, default=d.zendmm_ratio,
                   help="share of batches run with the Zend allocator instead of ASan-visible malloc")
    g.add_argument("--leaks", action="store_true", help="report memory leaks as bugs")
    g.add_argument("--max-dups", type=int, default=d.max_dups, help="reproducers kept per bug signature")
    g.add_argument("--keep-batches", action="store_true", help="keep executed batch directories (debugging)")

    g = p.add_argument_group("stopping")
    g.add_argument("--max-tests", type=int, default=0, help="stop after this many tests")
    g.add_argument("--time-limit", type=duration, default=0, help="stop after this long (e.g. 30m, 12h)")

    g = p.add_argument_group("paths")
    g.add_argument("--php-src", default=str(d.php_src), help="php-src checkout with a built sapi/cli/php")
    g.add_argument("--php", default=None, help="PHP binary to fuzz (default: <php-src>/sapi/cli/php)")
    g.add_argument("--harness-php", default=None,
                   help="PHP that runs run-tests.php (default: a non-sanitized php on PATH, else the target)")
    g.add_argument("--seeds-db", default=str(d.seeds_db))
    g.add_argument("--bugs-dir", default=str(d.bugs_dir))
    g.add_argument("--work-dir", default=str(d.work_dir),
                   help="scratch space for batches (a tmpfs such as /dev/shm/flowfusion is faster)")

    g = p.add_argument_group("other")
    g.add_argument("--ui", choices=("auto", "dash", "plain"), default="auto",
                   help="dash: live full-screen dashboard; plain: log lines (default when not a TTY)")
    g.add_argument("--seed", type=int, default=None, help="random seed")
    g.add_argument("--generate", type=int, default=0, metavar="N",
                   help="only write N fused tests (with fixtures) to --out and exit")
    g.add_argument("--out", default="fused-tests", help="output directory for --generate")
    return p


def config_from_args(args) -> Config:
    weights = {}
    for item in filter(None, (s.strip() for s in args.weights.split(','))):
        name, _, w = item.partition('=')
        weights[name.strip()] = float(w or 1)
    return Config(
        php_src=args.php_src, php_bin=args.php, harness_php=args.harness_php,
        seeds_db=args.seeds_db, bugs_dir=args.bugs_dir, work_dir=args.work_dir,
        strategies=args.strategies, strategy_weights=weights, chain_rate=args.chain_rate,
        mutation=not args.no_mutation, apifuzz=not args.no_apifuzz, apifuzz_rate=args.apifuzz_rate,
        classfuzz=not args.no_classfuzz, classfuzz_rate=args.classfuzz_rate,
        ini=not args.no_ini, jit_rate=args.jit_rate, translated_weight=args.translated_weight,
        jobs=args.jobs, runners=args.runners, batch_size=args.batch_size, timeout=args.timeout,
        zendmm_ratio=args.zendmm_ratio, leaks=args.leaks, max_dups=args.max_dups,
        keep_batches=args.keep_batches, max_tests=args.max_tests, time_limit=args.time_limit,
        ui=args.ui, rng_seed=args.seed,
    )


def generate_only(cfg: Config, count: int, out_dir: str) -> int:
    from flowfusion.corpus import SeedPool, load_seeds, without_missing_extensions
    from flowfusion.harness import loaded_extensions
    from flowfusion.fusion import Fuser
    from flowfusion.generator import TestGenerator
    from flowfusion.knowledge import Knowledge
    from flowfusion.mutator import Mutator

    cfg.finalize()
    if not os.path.exists(cfg.seeds_db):
        print(f"seed database not found: {cfg.seeds_db} (run ./prepare.sh first)", file=sys.stderr)
        return 1
    rng = random.Random(cfg.rng_seed)
    seeds = load_seeds(str(cfg.seeds_db))
    if cfg.php_bin.exists():
        seeds, _ = without_missing_extensions(seeds, loaded_extensions(str(cfg.php_bin)))
    pool = SeedPool(seeds, rng=rng, translated_weight=cfg.translated_weight)
    fuser = Fuser(list(cfg.strategies), rng=rng, mutator=Mutator(rng=rng) if cfg.mutation else None,
                  chain_rate=cfg.chain_rate, weights=cfg.strategy_weights)
    gen = TestGenerator(cfg, pool, fuser, Knowledge(str(cfg.apis_db), str(cfg.class_db), rng=rng), rng=rng)
    os.makedirs(out_dir, exist_ok=True)
    batch = gen.make_batch(0, count, out_dir)
    for t in batch.tests:
        print(f"{t.phpt}\t{t.strategy}\t{t.mode}\t{t.exec_mode}\t{' + '.join(t.parents)}")
    if getattr(gen, "errors", 0):
        print(f"{gen.errors} fusion errors; last: {gen.last_error}", file=sys.stderr)
    return 0


def main(argv=None) -> int:
    args = build_parser().parse_args(argv)
    cfg = config_from_args(args)
    if args.generate:
        return generate_only(cfg, args.generate, args.out)
    from flowfusion.fuzzer import Fuzzer, FuzzError
    try:
        return Fuzzer(cfg).run()
    except FuzzError as e:
        print(f"error: {e}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
