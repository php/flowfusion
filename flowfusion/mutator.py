"""
Light-touch mutation of seed programs before fusion.

Each rule fires with a small probability and rewrites one occurrence at its
own span (replacing "the first textual occurrence" instead corrupted
unrelated code, e.g. the `->` of the first method call for a `-`). Most
seeds pass through unchanged; mutation adds boundary values and operator
variants on top of what fusion already creates.
"""

import random
import re

from .phplex import PHP_VAR_RE, var_occurrences

SPECIAL_INTS = [
    '-1', '0', '1', '2', 'PHP_INT_MAX', 'PHP_INT_MIN',
    'PHP_FLOAT_MIN', 'PHP_FLOAT_MAX', 'PHP_FLOAT_EPSILON',
    'NULL', 'NAN', 'INF', '-INF',
    '0x7fffffff', '0x80000000', '0xffffffff',
    '2147483647', '-2147483648', '9223372036854775807', '-9223372036854775808',
]

# Strings that hit PHP's type-juggling and encoding edge cases.
SPECIAL_STRINGS = [
    '""', "''", '"0"', '"1"', '"-1"', '"false"', '"null"', '"0.0"', '"-0"',
    '" "', '"\\0"', '"\\x00"', '"0x1"', '"0b1"', '"0777"', '"1e100"', '"-1e100"',
    '"2147483648"', '"-2147483649"', '"Array"', "'test\\0test'", '"\\xff\\xfe"',
    '"\\u{1F600}"', '"%s%n%x"', 'str_repeat("A", 4096)',
]

_ARITH_RE = re.compile(r'(?<![-+*/%<>=!])(?:\+\+|\*\*|[-*/%])(?![-+*/%>=])')
_ASSIGN_RE = re.compile(r'\+=|-=|\*=|/=|%=|\.=|\?\?=')
_LOGIC_RE = re.compile(r'\band\b|\bor\b|\bxor\b|&&|\|\|')
_INT_RE = re.compile(r'(?<![A-Za-z0-9_$.])(?:0x[0-9a-fA-F]+|0[0-7]*|[1-9][0-9]*)(?![A-Za-z0-9_.])')
# Comments and heredocs are scanned together with strings so an apostrophe
# in a comment cannot desynchronise the string scan.
_STRING_SCAN_RE = re.compile(
    r"//[^\n]*|#[^\n]*|/\*.*?\*/|<<<['\"]?(\w+)['\"]?\n.*?\n\s*\1"
    r"|'(?:[^'\\]|\\.)*'|\"(?:[^\"\\]|\\.)*\"", re.S)
_CMP_RE = re.compile(r'===|!==|==|!=')
_CMP_SWAPS = {'===': '==', '!==': '!=', '==': '===', '!=': '!=='}
_BOOL_NULL_RE = re.compile(r'\b(?:true|false|TRUE|FALSE|True|False|null|NULL)\b')
_CASTS = ['(int)', '(string)', '(float)', '(bool)', '(array)', '(object)']
_CAST_RE = re.compile(r'\((?:int|integer|string|float|double|bool|boolean|array|object|binary)\)')
_SPACESHIP_CANDIDATE_RE = re.compile(r'(?<![-=<>!?])(?:<=|>=|==|<|>)(?![=<>?])')


def _replace_span(code: str, m, new: str) -> str:
    return code[:m.start()] + new + code[m.end():]


class Mutator:
    """Applies each mutation rule with its own (small) probability."""

    def __init__(self, rng=None, scale: float = 1.0):
        self.rng = rng or random
        self.scale = scale          # multiplies every rule's probability

    def _fires(self, p: float) -> bool:
        return self.rng.random() < p * self.scale

    def _pick(self, matches):
        return self.rng.choice(matches) if matches else None

    def arith(self, code):
        m = self._pick(list(_ARITH_RE.finditer(code)))
        return _replace_span(code, m, self.rng.choice(['+', '-', '*', '/', '%', '**'])) if m else code

    def assign(self, code):
        m = self._pick(list(_ASSIGN_RE.finditer(code)))
        if not m:
            return code
        return _replace_span(code, m, self.rng.choice(
            [op for op in ('+=', '-=', '*=', '/=', '%=', '.=', '??=') if op != m.group(0)]))

    def logical(self, code):
        m = self._pick(list(_LOGIC_RE.finditer(code)))
        if not m:
            return code
        return _replace_span(code, m, self.rng.choice(
            [op for op in ('and', 'or', 'xor', '&&', '||') if op != m.group(0)]))

    def integer(self, code):
        m = self._pick(list(_INT_RE.finditer(code)))
        return _replace_span(code, m, self.rng.choice(SPECIAL_INTS)) if m else code

    def string(self, code):
        strings = [m for m in _STRING_SCAN_RE.finditer(code) if m.group(0)[:1] in "'\""]
        m = self._pick(strings)
        if not m:
            return code
        if self.rng.random() < 0.2:
            byte = self.rng.randint(0, 255)
            new = '"\\x%02x"' % byte
        else:
            new = self.rng.choice(SPECIAL_STRINGS)
        return _replace_span(code, m, new)

    def variable(self, code):
        """Replace some occurrences of one variable with another variable
        of the same program, exposing type confusion across call sites."""
        names = list(dict.fromkeys(PHP_VAR_RE.findall(code)))
        if len(names) < 2:
            return code
        victim, repl = self.rng.sample(names, 2)
        if victim == '$this' or repl == '$this':
            return code
        occ = var_occurrences(code, victim)
        if not occ:
            return code
        chosen = self.rng.sample(occ, self.rng.randint(1, len(occ)))
        for m in sorted(chosen, key=lambda m: m.start(), reverse=True):
            code = _replace_span(code, m, repl)
        return code

    def comparison(self, code):
        m = self._pick(list(_CMP_RE.finditer(code)))
        return _replace_span(code, m, _CMP_SWAPS[m.group(0)]) if m else code

    def bool_null(self, code):
        m = self._pick(list(_BOOL_NULL_RE.finditer(code)))
        if not m:
            return code
        return _replace_span(code, m, self.rng.choice(
            [v for v in ('true', 'false', 'null', 'NULL', 'TRUE') if v != m.group(0)]))

    def cast(self, code):
        m = self._pick(list(_CAST_RE.finditer(code)))
        if m:
            return _replace_span(code, m, self.rng.choice(_CASTS))
        m = self._pick(list(PHP_VAR_RE.finditer(code)))
        if m and m.group(0) != '$this':
            # only in an expression position: right after `(`, `,` or `= `
            before = code[:m.start()].rstrip()[-1:]
            if before in ('(', ',', '='):
                return code[:m.start()] + self.rng.choice(_CASTS) + code[m.start():]
        return code

    def null_coalesce(self, code):
        if '??' in code:
            return code.replace('??', self.rng.choice(['?:', '||']), 1)
        if '?:' in code:
            return code.replace('?:', '??', 1)
        return code

    def spaceship(self, code):
        if '<=>' in code:
            return code.replace('<=>', self.rng.choice(['<', '>', '==']), 1)
        m = self._pick(list(_SPACESHIP_CANDIDATE_RE.finditer(code)))
        return _replace_span(code, m, '<=>') if m else code

    # (rule, probability)
    RULES = (
        ('arith', 0.001), ('assign', 0.001), ('logical', 0.001),
        ('integer', 0.002), ('string', 0.01), ('variable', 0.005),
        ('comparison', 0.003), ('bool_null', 0.005), ('cast', 0.003),
        ('null_coalesce', 0.003), ('spaceship', 0.002),
    )

    def mutate(self, code: str) -> str:
        for name, p in self.RULES:
            if self._fires(p):
                code = getattr(self, name)(code)
        return code
