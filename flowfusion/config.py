"""Run configuration. Every path defaults to a location inside the
FlowFusion checkout, so nothing depends on where it is cloned."""

import os
from dataclasses import dataclass, field
from pathlib import Path
from typing import Optional, Tuple

ROOT = Path(__file__).resolve().parent.parent


@dataclass
class Config:
    root: Path = ROOT
    php_src: Path = ROOT / "php-src"
    php_bin: Optional[Path] = None          # default: <php_src>/sapi/cli/php
    run_tests: Optional[Path] = None        # default: <php_src>/run-tests.php
    harness_php: Optional[str] = None       # PHP running run-tests.php (default: auto)
    seeds_db: Path = ROOT / "knowledges" / "seeds.db"
    apis_db: Path = ROOT / "knowledges" / "apis.db"
    class_db: Path = ROOT / "knowledges" / "class.db"
    deps_dir: Path = ROOT / "phpt_deps"
    bugs_dir: Path = ROOT / "bugs"
    work_dir: Path = ROOT / "workdir"
    log_dir: Path = ROOT / "logs"

    # fusion
    strategies: Tuple[str, ...] = ("dataflow", "state", "declaration")
    strategy_weights: dict = field(default_factory=dict)
    chain_rate: float = 0.15                # fuse the child with a third seed
    mutation: bool = True
    apifuzz: bool = True
    apifuzz_rate: float = 0.5
    classfuzz: bool = True
    classfuzz_rate: float = 0.3
    ini: bool = True
    jit_rate: float = 0.45
    translated_weight: float = 1.0

    # execution
    # Measured on 8 cores: -j8/2 runners 13.3 tests/s, -j12/3 17.5, -j12/6
    # 18.7, -j16/4 18.2 — slight oversubscription and small runners win,
    # since a test spends part of its life starting up and a runner's batch
    # tail (its slowest test) then overlaps with the other runners' work.
    jobs: int = (os.cpu_count() or 4) * 3 // 2   # parallel test processes in total
    runners: int = 0                        # concurrent run-tests.php instances (0: jobs / 3)
    batch_size: int = 0                     # tests per batch (0: 24 x jobs per runner)
    timeout: int = 10                       # seconds per test
    zendmm_ratio: float = 0.2               # share of batches keeping the Zend MM
    leaks: bool = False                     # report memory leaks as bugs
    max_dups: int = 3                       # reproducers kept per bug signature
    keep_batches: bool = False

    # stopping
    max_tests: int = 0
    time_limit: int = 0                     # seconds

    ui: str = "auto"                        # auto | dash | plain
    rng_seed: Optional[int] = None

    def finalize(self) -> "Config":
        for name in ("root", "seeds_db", "apis_db", "class_db", "deps_dir", "bugs_dir", "work_dir", "log_dir"):
            setattr(self, name, Path(getattr(self, name)).resolve())
        self.php_src = Path(self.php_src).resolve()
        self.php_bin = Path(self.php_bin or self.php_src / "sapi" / "cli" / "php")
        self.run_tests = Path(self.run_tests or self.php_src / "run-tests.php")
        if not self.runners:
            self.runners = max(2, self.jobs // 3)
        self.runners = max(1, min(self.runners, self.jobs))
        if not self.batch_size:
            self.batch_size = 24 * self.jobs_per_runner
        return self

    @property
    def jobs_per_runner(self) -> int:
        return max(1, -(-self.jobs // self.runners))
