"""
Turning fused programs into .phpt tests and staging them in batches.

Each test gets its own directory holding the .phpt and symlinks to the
fixtures of every php-src test directory its parents came from, so
`__DIR__ . '/foo.inc'` resolves exactly as it does in php-src — and two
parents' fixtures with the same name never collide across tests.
"""

import os
import random
from dataclasses import dataclass, field
from typing import Dict, List, Optional

from . import phplex
from .fusion import FusedProgram, Fuser
from .knowledge import IniFuzzer, Knowledge, sanitize_ini

EXPECT = "this is a flowfusion test"


@dataclass
class GeneratedTest:
    tid: str
    dir: str
    phpt: str                     # path of the .phpt file
    strategy: str                 # e.g. "state" or "dataflow>state"
    mode: str
    parents: List[str]
    exec_mode: str                # interp | opcache | jit:<mode>
    meta: Dict = field(default_factory=dict)


@dataclass
class Batch:
    bid: int
    dir: str
    tests: List[GeneratedTest]
    asan: bool = True             # run with USE_ZEND_ALLOC=0 (run-tests --asan)


class TestGenerator:
    def __init__(self, cfg, seed_pool, fuser: Fuser, knowledge: Optional[Knowledge],
                 rng: Optional[random.Random] = None):
        self.cfg = cfg
        self.pool = seed_pool
        self.fuser = fuser
        self.knowledge = knowledge
        self.rng = rng or random.Random()
        self.ini = IniFuzzer(rng=self.rng, jit_rate=cfg.jit_rate) if cfg.ini else None
        self._dep_cache: Dict[str, List[str]] = {}

    # -- .phpt assembly --------------------------------------------------

    def render(self, prog: FusedProgram):
        """(phpt text, exec mode) for a fused program."""
        variables = list(dict.fromkeys(phplex.php_variables(prog.body) + prog.bridge_vars))[:64]
        pre, post = [], []
        if self.knowledge and self.cfg.classfuzz and self.rng.random() < self.cfg.classfuzz_rate:
            cpre, cpost, cvars = self.knowledge.class_instrumentation(variables)
            pre.append(cpre)
            post.append(cpost)
            variables += cvars
        post.insert(0, "var_dump(get_defined_vars());")
        if self.knowledge and self.cfg.apifuzz and self.rng.random() < self.cfg.apifuzz_rate:
            post.append(self.knowledge.api_calls(variables))
        inner = "\n".join(pre + [prog.body] + post)

        ini_lines = list(prog.ini)
        exec_mode = "interp"
        if self.ini:
            extra, exec_mode = self.ini.random_ini()
            ini_lines += extra
        ini = sanitize_ini("\n".join(ini_lines))

        parts = [f"--TEST--\nFlowFusion {'>'.join(prog.strategies)} [{','.join(prog.modes)}]: "
                 f"{' + '.join(prog.parents)}\n"]
        if ini.strip():
            parts.append(f"--INI--\n{ini}\n")
        if prog.extensions:
            parts.append("--EXTENSIONS--\n" + "\n".join(prog.extensions) + "\n")
        preamble = prog.preamble
        if phplex.has_braced_namespace(prog.body):
            # mixing `namespace X;` with `namespace X { }` is a fatal error
            preamble = [p for p in preamble if not p.startswith("namespace")]
        php = "<?php\n" + "\n".join(preamble) + "\n" + phplex.wrap_body(inner) + "\n"
        parts.append(f"--FILE--\n{php}")
        parts.append(f"--EXPECT--\n{EXPECT}\n")
        return "".join(parts), exec_mode

    # -- fixtures ---------------------------------------------------------

    def _fixtures(self, rel_dir: str) -> List[str]:
        cached = self._dep_cache.get(rel_dir)
        if cached is None:
            base = os.path.normpath(os.path.join(self.cfg.deps_dir, rel_dir))
            cached = []
            if base.startswith(os.path.normpath(self.cfg.deps_dir)) and os.path.isdir(base):
                cached = [os.path.join(base, n) for n in sorted(os.listdir(base))
                          if not n.endswith('.phpt') and n != 'CONFLICTS']
            self._dep_cache[rel_dir] = cached
        return cached

    def _link_fixtures(self, test_dir: str, dirs: List[str]):
        for rel in dirs:
            for src in self._fixtures(rel):
                dst = os.path.join(test_dir, os.path.basename(src))
                if not os.path.lexists(dst):     # first parent's file wins
                    try:
                        os.symlink(src, dst)
                    except OSError:
                        pass

    # -- batches ----------------------------------------------------------

    def make_batch(self, bid: int, size: int, root: str) -> Batch:
        bdir = os.path.join(root, f"b{bid:06d}")
        os.makedirs(bdir, exist_ok=True)
        tests = []
        for k in range(size):
            try:
                prog = self.fuser.fuse(self.pool.pick)
                text, exec_mode = self.render(prog)
            except Exception as e:           # a generator bug must not stop fuzzing
                self.errors = getattr(self, 'errors', 0) + 1
                self.last_error = f"{type(e).__name__}: {e}"
                continue
            tid = f"t{k:04d}"
            tdir = os.path.join(bdir, tid)
            os.makedirs(tdir)
            self._link_fixtures(tdir, prog.source_dirs)
            path = os.path.join(tdir, "test.phpt")
            # latin-1 both ways keeps seed bytes (binary strings) intact
            with open(path, "w", encoding="latin-1", errors="replace") as f:
                f.write(text)
            tests.append(GeneratedTest(
                tid=tid, dir=tdir, phpt=path, strategy=">".join(prog.strategies),
                mode=",".join(prog.modes), parents=prog.parents, exec_mode=exec_mode))
        asan = self.rng.random() >= self.cfg.zendmm_ratio
        return Batch(bid=bid, dir=bdir, tests=tests, asan=asan)
