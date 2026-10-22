"""
Classifying test outcomes and storing deduplicated bugs.

A bug is a sanitizer report, a failed assertion, a fatal signal or a
corrupted Zend heap. Each one gets a signature from the first stack frame
inside php-src that is not an allocator or sanitizer frame, so the same
bug reached by a thousand different fused tests is one entry in
bugs/index.json with a handful of reproducers, not a thousand directories.
"""

import json
import os
import re
import shutil
import threading
import time
from dataclasses import dataclass
from typing import Optional

PARSE_ERROR_RE = re.compile(r'(?:PHP )?Parse error: ')
FATAL_RE = re.compile(r'(?:PHP )?Fatal error: (?!Uncaught\b|Allowed memory size|Maximum execution time)')
TIMEOUT_MARK = "** ERROR: process timed out **"

_ASAN_ERR_RE = re.compile(r'==\d+==ERROR: (AddressSanitizer|LeakSanitizer|MemorySanitizer): ([\w-]+)(?: on unknown address)?')
_ASAN_SUMMARY_RE = re.compile(r'SUMMARY: (AddressSanitizer|LeakSanitizer|MemorySanitizer): ([\w-]+)')
# The patterns below are applied to single, length-bounded lines (see
# _lines_with): unanchored on a 256 KB output, `[^\s:]+\.\w+:` backtracks
# quadratically over a long token such as a printed str_repeat("A", ...),
# and a regex holds the GIL — every thread of the fuzzer froze for minutes.
_UBSAN_RE = re.compile(r'(\S+?\.\w+):(\d+):\d+: runtime error: (.+)')
_ASSERT_RE = re.compile(r'Assertion [`\'](.{1,200}?)\' failed')
_ASSERT_LOC_RE = re.compile(r'(\S+\.\w+):(\d+): (.*): $')
_TERMSIG_RE = re.compile(r'Termsig=(\d+)')
_FRAME_RE = re.compile(r'^[ \t]*#\d+ 0x[0-9a-f]+ in ([A-Za-z_][\w:~<>]*) (\S*)')
_SKIP_FRAMES = ("malloc", "calloc", "realloc", "free", "__zend_malloc", "__zend_calloc", "__zend_realloc",
                "_emalloc", "_erealloc", "_ecalloc", "_efree", "_safe_emalloc", "_safe_erealloc",
                "_estrdup", "_estrndup", "zend_string_alloc", "zend_string_init", "abort", "raise",
                "kill", "__assert_fail", "__assert_fail_base", "memcpy", "memmove", "memset",
                "strlen", "strcmp", "memcmp", "__interceptor_memcpy")
_SKIP_PREFIXES = ("zend_mm_", "__asan", "__interceptor", "__sanitizer", "__ubsan", "_zend_mm_", "__libc", "__GI_")
SIGNALS = {4: "SIGILL", 6: "SIGABRT", 7: "SIGBUS", 8: "SIGFPE", 11: "SIGSEGV"}


@dataclass
class Outcome:
    kind: str                         # bug | timeout | parse | fatal | ok | skip | lost
    signature: str = ""
    summary: str = ""


def _lines_with(text: str, needle: str, before: int = 400, after: int = 400, limit: int = 64):
    """The line(s) of `text` containing `needle` (at most `limit`), each cut
    to a window of `before`/`after` characters around it."""
    start = 0
    for _ in range(limit):
        i = text.find(needle, start)
        if i == -1:
            return
        ls = max(text.rfind('\n', 0, i) + 1, i - before)
        le = text.find('\n', i)
        le = min(len(text) if le == -1 else le, i + len(needle) + after)
        yield text[ls:le]
        start = i + len(needle)


def _search(rx, text: str, needle: str):
    for line in _lines_with(text, needle):
        m = rx.search(line)
        if m:
            return m
    return None


def _first_frame(text: str) -> Optional[str]:
    for line in _lines_with(text, " in ", before=40, after=600):
        m = _FRAME_RE.match(line)
        if not m:
            continue
        func, path = m.group(1), m.group(2).split(':')[0]
        if func in _SKIP_FRAMES or func.startswith(_SKIP_PREFIXES):
            continue
        # glibc frames carry relative source paths (nptl/pthread_kill.c),
        # frames in unsymbolized system libraries a "(/lib/...+0x..)" module
        if path and (not path.startswith(('/', '(')) or path.startswith(('(/lib', '(/usr/lib'))
                     or '/compiler-rt' in path or 'sanitizer_common' in path or 'sysdeps' in path):
            continue
        fname = os.path.basename(path) if path else "?"
        return f"{func} ({fname})"
    return None


def _norm(msg: str) -> str:
    msg = re.sub(r'0x[0-9a-f]+', 'ADDR', msg)
    return re.sub(r'-?\d+', 'N', msg)[:120]


# Aborts that are documented library behaviour, not PHP bugs.
_BENIGN_ABORTS = (
    "gmp: overflow in mpz type",           # libgmp aborts on results it cannot allocate
    "GNU MP: Cannot allocate memory",
)


def classify(output: str, status: str, leaks: bool = False) -> Outcome:
    if status == "SKIPPED":
        return Outcome("skip")
    if any(b in output for b in _BENIGN_ABORTS):
        return Outcome("fatal")
    if status == "LOST" and not output:
        return Outcome("lost")
    # 1. sanitizers (an ABRT report from a failed assertion is filed under
    #    the assertion, whose text identifies the bug better)
    m = _ASAN_ERR_RE.search(output) or _ASAN_SUMMARY_RE.search(output)
    if m and m.group(2) == "ABRT" and _search(_ASSERT_RE, output, "Assertion "):
        m = None
    if m and (m.group(1) != "LeakSanitizer" or leaks):
        tool, kind = m.group(1), m.group(2)
        if tool == "LeakSanitizer":
            kind = "memory-leak"
        frame = _first_frame(output[m.start():]) or "unknown"
        short = {"AddressSanitizer": "asan", "LeakSanitizer": "lsan", "MemorySanitizer": "msan"}[tool]
        return Outcome("bug", f"{short}:{kind} in {frame}", f"{tool}: {kind} in {frame}")
    m = _search(_UBSAN_RE, output, 'runtime error:')
    if m:
        where = f"{os.path.basename(m.group(1))}:{m.group(2)}"
        return Outcome("bug", f"ubsan:{_norm(m.group(3))} at {where}", f"UBSan: {m.group(3)[:100]} at {where}")
    # 2. assertions (ZEND_ASSERT in debug builds)
    for line in _lines_with(output, 'Assertion ', limit=16):
        m = _ASSERT_RE.search(line)
        if not m:
            continue
        loc = _ASSERT_LOC_RE.search(line[max(0, m.start() - 300):m.start()])
        where = ""
        if loc:
            func = re.findall(r'(\w+)\s*\(', loc.group(3))
            where = f" in {func[0] if func else loc.group(3).strip()}"
        return Outcome("bug", f"assert:{_norm(m.group(1))}{where}", f"Assertion `{m.group(1)[:100]}' failed{where}")
    if "zend_mm_heap corrupted" in output:
        return Outcome("bug", "zendmm:heap corrupted", "zend_mm_heap corrupted")
    # 3. fatal signals without a sanitizer report
    # run-tests.php prints the *stop* signal here (0 for a process killed by
    # a signal) unless the shell reported 128+N, so only a real number counts
    m = _TERMSIG_RE.search(output)
    if m and int(m.group(1)) in SIGNALS:
        sig = SIGNALS[int(m.group(1))]
        frame = _first_frame(output)
        return Outcome("bug", f"signal:{sig}" + (f" in {frame}" if frame else ""), f"{sig}" + (f" in {frame}" if frame else ""))
    if "(core dumped)" in output:
        return Outcome("bug", "signal:core-dumped", "core dumped")
    if leaks and re.search(r'\bleaked in\b', output):
        return Outcome("bug", "zendmm:leak", "Zend MM reported a leak")
    # 4. no bug
    if TIMEOUT_MARK in output or "batch deadline exceeded" in output or "FlowFusion watchdog" in output:
        return Outcome("timeout")
    if PARSE_ERROR_RE.search(output):
        return Outcome("parse")
    if status == "LOST":
        return Outcome("lost")
    if FATAL_RE.search(output):
        return Outcome("fatal")
    return Outcome("ok")


class BugStore:
    """bugs/<n>/{test.phpt,test.php,test.out,test.sh,meta.json} plus
    bugs/index.json: signature -> {ids, count, first/last seen}."""

    def __init__(self, bugs_dir: str, max_dups: int = 3):
        self.dir = bugs_dir
        self.max_dups = max_dups
        os.makedirs(bugs_dir, exist_ok=True)
        self.index_path = os.path.join(bugs_dir, "index.json")
        self.index = {}
        if os.path.exists(self.index_path):
            try:
                with open(self.index_path) as f:
                    self.index = json.load(f)
            except (OSError, ValueError):
                self.index = {}
        existing = [int(n) for n in os.listdir(bugs_dir) if n.isdigit()]
        self.next_id = max(existing, default=0) + 1
        self.known_at_start = len(self.index)
        self._lock = threading.Lock()

    def unique(self) -> int:
        return len(self.index)

    def record(self, result, outcome: Outcome, extra_meta: dict):
        """Returns (is_new_signature, bug dir or None)."""
        now = time.strftime("%Y-%m-%d %H:%M:%S")
        with self._lock:
            entry = self.index.get(outcome.signature)
            is_new = entry is None
            if is_new:
                entry = self.index[outcome.signature] = {
                    "summary": outcome.summary, "count": 0, "ids": [],
                    "first_seen": now, "strategies": {}}
            entry["count"] += 1
            entry["last_seen"] = now
            strat = result.test.strategy
            entry["strategies"][strat] = entry["strategies"].get(strat, 0) + 1
            bug_dir = None
            if len(entry["ids"]) < self.max_dups:
                bid = self.next_id
                self.next_id += 1
                entry["ids"].append(bid)
                bug_dir = os.path.join(self.dir, str(bid))
            self._save_index()
        if bug_dir:
            self._save_bundle(bug_dir, result, outcome, extra_meta)
        return is_new, bug_dir

    def _save_index(self):
        tmp = self.index_path + ".tmp"
        with open(tmp, "w") as f:
            json.dump(self.index, f, indent=2, sort_keys=True)
        os.replace(tmp, self.index_path)

    def _save_bundle(self, bug_dir: str, result, outcome: Outcome, extra_meta: dict):
        t = result.test
        os.makedirs(bug_dir, exist_ok=True)
        base = t.phpt[:-5]
        for ext in ("phpt", "php", "out", "diff"):
            src = f"{base}.{ext}"
            if os.path.exists(src):
                shutil.copyfile(src, os.path.join(bug_dir, f"test.{ext}"))
        if not os.path.exists(os.path.join(bug_dir, "test.out")):
            with open(os.path.join(bug_dir, "test.out"), "w", encoding="latin-1") as f:
                f.write(result.output)
        # fixtures the test used (symlinks into phpt_deps) are copied so the
        # bundle reproduces on its own
        for name in os.listdir(t.dir):
            src = os.path.join(t.dir, name)
            if os.path.islink(src):
                dst = os.path.join(bug_dir, name)
                try:
                    if os.path.isdir(src):
                        shutil.copytree(src, dst, symlinks=False)
                    else:
                        shutil.copyfile(src, dst)
                except OSError:
                    pass
        sh = f"{base}.sh"
        if os.path.exists(sh):
            # run-tests.php's script exports the test's whole environment
            # (USE_ZEND_ALLOC, ASAN_OPTIONS, ...); only its paths change
            with open(sh, encoding="latin-1") as f:
                script = f.read().replace(t.dir, bug_dir)
            path = os.path.join(bug_dir, "test.sh")
            with open(path, "w", encoding="latin-1") as f:
                f.write(script)
            os.chmod(path, 0o755)
        meta = {
            "signature": outcome.signature, "summary": outcome.summary,
            "strategy": t.strategy, "mode": t.mode, "parents": t.parents,
            "exec_mode": t.exec_mode, "found": time.strftime("%Y-%m-%d %H:%M:%S"),
            **extra_meta,
        }
        with open(os.path.join(bug_dir, "meta.json"), "w") as f:
            json.dump(meta, f, indent=2)
