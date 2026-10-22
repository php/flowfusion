"""
Knowledge about the target PHP build (its functions and classes, collected
by knowledges/function.php and class.php) and the instrumentation built
from it: API fuzzing, class fuzzing and runtime-configuration fuzzing.
"""

import os
import random
import re
import sqlite3
from typing import List, Tuple

# Functions never worth calling from generated code: they kill the run,
# block, fork, or are zend_test helpers that break engine invariants on
# purpose (their "crashes" are not bugs). They are also disabled at
# runtime through disable_functions, which covers calls inside seeds.
BLOCKED_FUNCTIONS = (
    "pcntl_fork", "pcntl_rfork", "pcntl_exec", "pcntl_alarm", "pcntl_wait", "pcntl_waitpid",
    "posix_kill", "posix_setsid", "posix_setpgid", "posix_setuid", "posix_setgid",
    "posix_seteuid", "posix_setegid", "posix_mkfifo", "posix_setrlimit",
    "system", "exec", "shell_exec", "passthru", "proc_open", "popen", "proc_nice",
    "mail", "mb_send_mail", "chroot", "readline", "sleep", "usleep", "time_sleep_until",
    "time_nanosleep", "set_time_limit",
    "zend_test_crash", "zend_leak_bytes", "zend_leak_variable", "zend_terminate_string",
    "zend_create_unterminated_string", "zend_test_array_return",
)

# Literal argument pools by declared parameter type.
_TYPED_LITERALS = {
    'int': ['0', '-1', '1', 'PHP_INT_MAX', 'PHP_INT_MIN', '0x7fffffff', '-2147483648', '1024'],
    'float': ['0.0', '-0.0', 'NAN', 'INF', '-INF', 'PHP_FLOAT_EPSILON', '1e308', '0.1'],
    'string': ['""', '"0"', '"a"', 'str_repeat("A", 1024)', '"\\0"', '"%s%s%n"', '"\\xff\\xfe"', '"../"', '"UTF-8"'],
    'bool': ['true', 'false'],
    'array': ['[]', '[1, 2, 3]', '["a" => 1, "b" => [null]]', 'range(0, 100)', '[[[]]]'],
    'callable': ['"strlen"', 'fn($x = null) => $x', '"var_dump"', 'null'],
    'object': ['new stdClass', 'new ArrayObject([1])', 'new SplObjectStorage'],
    'iterable': ['[]', 'new ArrayIterator([1, 2])'],
    'mixed': ['null', '0', '""', '[]', 'new stdClass', '-1', '1.5'],
}


def _literal_for(ptype: str, rng) -> str:
    t = (ptype or 'mixed').lstrip('?').lower()
    for part in re.split(r'[|&]', t):
        part = part.strip('() ')
        if part in _TYPED_LITERALS:
            return rng.choice(_TYPED_LITERALS[part])
    if t in ('null', 'void'):
        return 'null'
    return rng.choice(_TYPED_LITERALS['mixed'])


class Knowledge:
    def __init__(self, apis_db: str, class_db: str, rng=None):
        self.rng = rng or random.Random()
        self.functions: List[Tuple[str, List[str]]] = []   # (name, [param types])
        self.classes: List[dict] = []
        self._load_apis(apis_db)
        self._load_classes(class_db)

    @staticmethod
    def _skip(name: str) -> bool:
        return name in BLOCKED_FUNCTIONS or name.startswith(('zend_', 'pcntl_', 'posix_', 'ob_'))

    def _load_apis(self, path: str):
        if not os.path.exists(path):
            return
        conn = sqlite3.connect(path)
        try:
            cols = {r[0] for r in conn.execute("SELECT name FROM sqlite_master WHERE type='table'")}
            params = {}
            if 'parameters' in cols:
                for fid, ptype in conn.execute("SELECT function_id, type FROM parameters ORDER BY id"):
                    params.setdefault(fid, []).append(ptype or 'mixed')
            for fid, name, nparams in conn.execute("SELECT id, name, num_params FROM functions"):
                if self._skip(name):
                    continue
                types = params.get(fid) or ['mixed'] * int(nparams or 0)
                self.functions.append((name, types))
        finally:
            conn.close()

    def _load_classes(self, path: str):
        if not os.path.exists(path):
            return
        conn = sqlite3.connect(path)
        try:
            attrs, methods = {}, {}
            for cid, name in conn.execute("SELECT class_id, name FROM attributes"):
                attrs.setdefault(cid, []).append(name)
            for cid, name, n in conn.execute("SELECT class_id, name, params_count FROM methods"):
                methods.setdefault(cid, []).append((name, int(n or 0)))
            for cid, cname in conn.execute("SELECT id, class_name FROM classes"):
                if cname.lstrip('_').startswith(('ZendTest', 'Zend')):
                    continue
                self.classes.append({'name': cname, 'attributes': attrs.get(cid, []),
                                     'methods': methods.get(cid, [])})
        finally:
            conn.close()

    # -- instrumentation --------------------------------------------------

    def _arg(self, ptype: str, variables: List[str]) -> str:
        if variables and self.rng.random() < 0.7:
            return self.rng.choice(variables)
        return _literal_for(ptype, self.rng)

    def api_calls(self, variables: List[str], calls: int = 5) -> str:
        """Calls of one random internal function with the program's own
        variables (and typed boundary literals) as arguments."""
        if not self.functions:
            return ""
        name, types = self.rng.choice(self.functions)
        out = []
        for _ in range(calls):
            n = len(types)
            if n and self.rng.random() < 0.3:
                n = self.rng.randint(0, n)         # drop optional trailing args
            args = ", ".join(self._arg(types[k] if k < len(types) else 'mixed', variables)
                             for k in range(n))
            out.append(f"try {{ {name}({args}); }} catch (\\Throwable $_ff_e) {{}}")
        return "\n".join(out)

    def class_instrumentation(self, variables: List[str]) -> Tuple[str, str, List[str]]:
        """(code before the body, code after it, new variables): an instance
        of a random internal class whose methods are called after the
        fused body with its variables."""
        if not self.classes:
            return "", "", []
        info = self.rng.choice(self.classes)
        cname = info['name']
        pre = [f"try {{ $cls = new \\{cname}(); }} catch (\\Throwable $_ff_e) {{ $cls = new \\stdClass(); }}"]
        new_vars = ["$cls"]
        if info['attributes']:
            attr = self.rng.choice(info['attributes'])
            pre.append(f"try {{ $clsAttr = $cls->{attr}; }} catch (\\Throwable $_ff_e) {{}}")
            new_vars.append("$clsAttr")
        after = []
        if info['methods']:
            mname, n = self.rng.choice(info['methods'])
            pool = variables + new_vars
            for _ in range(5):
                args = ", ".join(self._arg('mixed', pool) for _ in range(n))
                after.append(f"try {{ $cls->{mname}({args}); }} catch (\\Throwable $_ff_e) {{}}")
        return "\n".join(pre), "\n".join(after), new_vars


# ---------------------------------------------------------------------------
# Runtime configuration fuzzing
# ---------------------------------------------------------------------------

_INI_POOL = {
    "precision": [-1, 0, 1, 10, 14, 17, 50],
    "serialize_precision": [-1, 0, 5, 17, 75],
    "memory_limit": ["2M", "8M", "16M", "32M", "128M", "256M"],
    "default_charset": ["UTF-8", "ISO-8859-1", "cp1252", "big5", "cp932", "", "UTF-16"],
    "error_reporting": ["0", "-1", "E_ALL", "E_ALL & ~E_DEPRECATED", "E_ALL & ~E_WARNING"],
    "zend.assertions": [-1, 0, 1],
    "zend.enable_gc": [0, 1],
    "zend.exception_ignore_args": [0, 1],
    "short_open_tag": [0, 1],
    "max_input_vars": [1, 5, 1000],
    "pcre.jit": [0, 1],
    "pcre.backtrack_limit": [1, 100, 1000000],
    "pcre.recursion_limit": [1, 100, 100000],
    "mbstring.language": ["neutral", "Japanese", "uni"],
    "mbstring.substitute_character": ["none", "long", "entity", "63"],
    "unserialize_max_depth": [0, 1, 128],
    "date.timezone": ["UTC", "Europe/London", "Asia/Singapore", "America/Los_Angeles", "Pacific/Chatham"],
    "session.use_strict_mode": [0, 1],
    "session.serialize_handler": ["php", "php_binary", "php_serialize"],
    "opcache.optimization_level": ["0", "-1", "0x7fffffff", "0x7FFFBFFF", "0x4ff"],
    "opcache.jit_max_polymorphic_calls": [0, 2, 1000],
    "opcache.jit_max_recursive_calls": [0, 2, 10],
    "opcache.jit_max_loop_unrolls": [0, 8, 10],
}

# opcache.jit is a CRTO quadruple; the named modes are aliases (tracing is
# 1254, function is 1205). The spread walks the register allocator and the
# trigger independently, the two digits that change the emitted code.
_JIT_MODES = ["tracing", "function", "1254", "1205", "1235", "1201", "1255", "0254", "1215", "1011"]
_JIT_WEIGHTS = [20, 14, 10, 10, 8, 8, 8, 8, 7, 7]

_MEMORY_CAP = 512 * 1024 * 1024
_MEM_RE = re.compile(r'^\s*memory_limit\s*=\s*(.+?)\s*$', re.I | re.M)


def _parse_size(v: str) -> int:
    v = v.strip().strip('"\'')
    m = re.match(r'^(-?\d+)\s*([kKmMgG]?)$', v)
    if not m:
        return 0
    n = int(m.group(1))
    return n * {'': 1, 'k': 1 << 10, 'm': 1 << 20, 'g': 1 << 30}[m.group(2).lower()]


# INI settings that only produce harness artifacts. zend_test's Zend MM
# hooks (zend_test.zend_mm_custom_handlers.enabled,
# zend_test.observe_opline_in_zendmm) wrap the Zend MM, which does not
# exist when a batch runs with USE_ZEND_ALLOC=0 (ASan-visible malloc):
# every allocation then hits a NULL chunk, a "bug" in the test setup.
_DROPPED_INI_RE = re.compile(r'^\s*zend_test\.[\w.]*(?:zendmm|zend_mm)[\w.]*\s*=.*$\n?', re.M)


def sanitize_ini(ini: str) -> str:
    """Cap memory_limit (with several workers in parallel an unlimited test
    could exhaust the host) and drop settings that only cause artifacts."""
    ini = _DROPPED_INI_RE.sub('', ini)
    def cap(m):
        size = _parse_size(m.group(1))
        if size < 0 or size > _MEMORY_CAP:
            return "memory_limit=512M"
        return m.group(0)
    return _MEM_RE.sub(cap, ini)


class IniFuzzer:
    def __init__(self, rng=None, jit_rate: float = 0.45, opcache_rate: float = 0.15,
                 extra_rate: float = 0.7):
        self.rng = rng or random.Random()
        self.jit_rate = jit_rate
        self.opcache_rate = opcache_rate
        self.extra_rate = extra_rate

    def random_ini(self) -> Tuple[List[str], str]:
        """(ini lines, label of the execution mode) for one test."""
        lines, mode = [], "interp"
        r = self.rng.random()
        if r < self.jit_rate:
            jit = self.rng.choices(_JIT_MODES, weights=_JIT_WEIGHTS, k=1)[0]
            lines += ["opcache.enable=1", "opcache.enable_cli=1",
                      f"opcache.jit={jit}", "opcache.jit_buffer_size=64M"]
            mode = f"jit:{jit}"
        elif r < self.jit_rate + self.opcache_rate:
            lines += ["opcache.enable=1", "opcache.enable_cli=1", "opcache.jit=disable"]
            mode = "opcache"
        if self.rng.random() < self.extra_rate:
            key = self.rng.choice(list(_INI_POOL))
            lines.append(f"{key}={self.rng.choice(_INI_POOL[key])}")
        return lines, mode
