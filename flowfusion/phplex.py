"""
Lightweight PHP lexical helpers shared by every fusion strategy.

Nothing here parses PHP properly; the helpers know just enough of the
lexical grammar (strings, comments, heredocs, braces) to cut and splice
programs without breaking them in the common case. A fused program that
fails to parse is still executed — the error paths of the PHP compiler are
a legitimate fuzzing target — so these helpers aim for "usually right and
always fast" rather than exactness.
"""

import collections
import re
from typing import List, Tuple

PHP_IDENT = r'[A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*'
PHP_VAR_RE = re.compile(r'\$' + PHP_IDENT)

# Variables that can never be the source or target of a rename: assigning
# to `$this` or an auto-global is a compile-time error, so a fused program
# doing so would never run at all.
UNRENAMABLE_VARS = frozenset({
    "$this", "$GLOBALS", "$_SERVER", "$_GET", "$_POST", "$_FILES",
    "$_COOKIE", "$_SESSION", "$_REQUEST", "$_ENV", "$http_response_header",
    "$argv", "$argc",
})


# ---------------------------------------------------------------------------
# Lexical mask
# ---------------------------------------------------------------------------

_MASK_TOKEN_RE = re.compile(r'<<<|//|/\*|#(?!\[)|[\'"`]')
_HEREDOC_OPEN_RE = re.compile(r'<<<[ \t]*(["\']?)(' + PHP_IDENT + r')\1[ \t]*\r?\n')


def _heredoc_end(code: str, open_match) -> int:
    """Offset just past the closing identifier of a heredoc/nowdoc whose
    opener is `open_match`, or len(code) when it is never closed. Since
    PHP 7.3 the closing marker may be indented and followed by any
    non-identifier character (`;`, `,`, `)`)."""
    tag = open_match.group(2)
    close = re.compile(r'^[ \t]*' + re.escape(tag) + r'(?![A-Za-z0-9_\x80-\xff])', re.M)
    m = close.search(code, open_match.end())
    return m.end() if m else len(code)


def code_spans(code: str) -> List[Tuple[int, int]]:
    """(start, end) spans of `code` that are strings, comments or heredoc
    bodies, in order. Everything outside them is real code."""
    spans = []
    n = len(code)
    pos = 0
    while True:
        m = _MASK_TOKEN_RE.search(code, pos)
        if m is None:
            break
        i = m.start()
        tok = m.group(0)
        if tok == '<<<':
            hm = _HEREDOC_OPEN_RE.match(code, i)
            if hm is None:              # `<<<` that is not a heredoc opener
                pos = i + 3
                continue
            j = _heredoc_end(code, hm)
        elif tok == '/*':
            j = code.find('*/', i + 2)
            j = n if j == -1 else j + 2
        elif tok in ('//', '#'):
            j = code.find('\n', i)
            j = n if j == -1 else j
        else:                           # a quote
            j = i + 1
            while j < n:
                c = code[j]
                if c == '\\':
                    j += 2
                    continue
                if c == tok:
                    j += 1
                    break
                j += 1
            j = min(j, n)
        spans.append((i, j))
        pos = j if j > i else i + 1
    return spans


def lexical_mask(code: str) -> List[bool]:
    """One bool per character: True where code[i] is real code (outside
    strings, comments and heredoc bodies)."""
    mask = [True] * len(code)
    for i, j in code_spans(code):
        mask[i:j] = [False] * (j - i)
    return mask


def masked_text(code: str) -> str:
    """`code` with every string/comment/heredoc character replaced by a
    space; same length and line structure as `code`."""
    parts, keep = [], 0
    for i, j in code_spans(code):
        parts.append(code[keep:i])
        parts.append(re.sub(r'[^\n]', ' ', code[i:j]))
        keep = j
    parts.append(code[keep:])
    return ''.join(parts)


def strip_comments(code: str) -> str:
    """Remove `//`, `#` and `/* */` comments, keeping strings and newlines."""
    out, keep = [], 0
    for i, j in code_spans(code):
        if code.startswith(('//', '#', '/*'), i):
            out.append(code[keep:i])
            out.append('\n' * code.count('\n', i, j))
            keep = j
    out.append(code[keep:])
    return ''.join(out)


# ---------------------------------------------------------------------------
# Statement splitting
# ---------------------------------------------------------------------------

_SPLIT_INTERESTING_RE = re.compile(r'[\\\'"#/<(){};]')
_CONTINUATION_AT_RE = re.compile(
    r'\s*(?:catch\s*\(|finally\s*\{|else\s*\{|elseif\s*\(|else\s+if\s*\(|else\s*:|while\s*\()')


def split_statements(code: str) -> List[str]:
    """Split PHP code into top-level statement units, respecting brace/paren
    depth, string literals, comments and heredocs. Compound blocks
    (functions, classes, if/elseif/else chains, try/catch/finally,
    do/while) are kept as single units."""
    statements: List[str] = []
    current: List[str] = []
    brace_depth = 0
    paren_depth = 0
    n = len(code)
    find = _SPLIT_INTERESTING_RE.search

    def emit():
        stmt = ''.join(current).strip()
        if stmt:
            statements.append(stmt)

    def trailing_comment(k):
        """End of a `// ...` / `# ...` comment that follows position k on
        the same line, so it stays with the statement it annotates."""
        p = k
        while p < n and code[p] in ' \t':
            p += 1
        if code.startswith('//', p) or (code.startswith('#', p) and not code.startswith('#[', p)):
            nl = code.find('\n', p)
            return n if nl == -1 else nl
        return k

    i = 0
    while i < n:
        m = find(code, i)
        if m is None:
            current.append(code[i:])
            break
        j = m.start()
        if j > i:
            current.append(code[i:j])
        ch = code[j]
        nch = code[j + 1] if j + 1 < n else ''
        i = j

        if ch == '\\':
            current.append(ch)
            i += 1
            continue

        if ch in ("'", '"'):
            k = j + 1
            while k < n:
                c = code[k]
                if c == '\\':
                    k += 2
                    continue
                if c == ch:
                    k += 1
                    break
                k += 1
            k = min(k, n)
            current.append(code[j:k])
            i = k
            continue

        if (ch == '/' and nch == '/') or (ch == '#' and nch != '['):
            nl = code.find('\n', j)
            k = n if nl == -1 else nl
            current.append(code[j:k])
            i = k
            continue
        if ch == '/' and nch == '*':
            e = code.find('*/', j + 2)
            k = n if e == -1 else e + 2
            current.append(code[j:k])
            i = k
            continue

        if ch == '<' and code.startswith('<<<', j):
            hm = _HEREDOC_OPEN_RE.match(code, j)
            if hm is not None:
                k = _heredoc_end(code, hm)
                current.append(code[j:k])
                i = k
                continue

        current.append(ch)
        if ch == '(':
            paren_depth += 1
        elif ch == ')':
            paren_depth -= 1
        elif ch == '{':
            brace_depth += 1
        elif ch == '}':
            brace_depth -= 1
            if brace_depth <= 0 and paren_depth <= 0:
                brace_depth = 0
                k = j + 1
                while k < n and code[k] in ' \t\r\n':
                    k += 1
                if _CONTINUATION_AT_RE.match(code, k):
                    # `} else {`, `} catch (`, the `while` of a do/while
                    i += 1
                    continue
                end = j + 1
                if k < n and code[k] == ';':
                    end = k + 1
                end2 = trailing_comment(end)
                current.append(code[j + 1:end2])
                emit()
                current = []
                i = end2
                continue
        elif ch == ';' and brace_depth <= 0 and paren_depth <= 0:
            end = trailing_comment(j + 1)
            current.append(code[j + 1:end])
            emit()
            current = []
            i = end
            continue
        i += 1

    leftover = ''.join(current).strip()
    if leftover:
        statements.append(leftover)
    return statements


_LEADING_COMMENTS_RE = re.compile(
    r'^(?:\s*(?://[^\n]*|#(?!\[)[^\n]*|/\*.*?\*/|#\[[^\n]*))*', re.S)


def statement_head(stmt: str) -> str:
    """The statement without leading comments, fusion tags and attributes,
    so the keyword that classifies it is at the start."""
    return _LEADING_COMMENTS_RE.sub('', stmt, count=1).lstrip()


# ---------------------------------------------------------------------------
# Declarations and name conflicts
# ---------------------------------------------------------------------------

TOPLEVEL_DECL_RE = re.compile(
    r'^(?:(?:abstract|final|readonly)\s+)*'
    r'(?:function\s+&?\s*|class\s+|interface\s+|trait\s+|enum\s+)'
    r'(' + PHP_IDENT + r')')

# Statements PHP only accepts (or only early-binds) at the outermost scope.
TOPLEVEL_ONLY_RE = re.compile(
    r'^(?:use\s|const\s|namespace\b|declare\s*\(|__halt_compiler\b|#\['
    r'|(?:(?:abstract|final|readonly)\s+)*(?:function\s+&?\s*[A-Za-z_\x80-\xff]'
    r'|class\s+[A-Za-z_\x80-\xff]|interface\s|trait\s|enum\s+[A-Za-z_\x80-\xff]))')

MAGIC_NAMES = frozenset({
    '__construct', '__destruct', '__toString', '__invoke', '__clone',
    '__get', '__set', '__isset', '__unset', '__call', '__callStatic',
    '__sleep', '__wakeup', '__serialize', '__unserialize', '__debugInfo',
    'get', 'set',
})

FUSION_TAG_RE = re.compile(r"[ \t]*//[ \t]*\w+ fusion[ \t]*$", re.M)


def toplevel_names(code: str) -> set:
    """Functions/classes/interfaces/traits/enums declared at the top level
    of `code` — the only names that can collide between two seeds."""
    names = set()
    for stmt in split_statements(code):
        m = TOPLEVEL_DECL_RE.match(statement_head(stmt))
        if m:
            names.add(m.group(1))
    return names - MAGIC_NAMES


def _drop_verbatim_decls(host: str, donor: str) -> str:
    """Drop donor declarations the host already holds verbatim (chained
    fusion feeds the same seed in twice); the host's copy serves both."""
    host_plain = FUSION_TAG_RE.sub('', host)
    kept, dropped = [], 0
    stmts = split_statements(donor)
    for stmt in stmts:
        text = FUSION_TAG_RE.sub('', stmt).strip()
        if TOPLEVEL_DECL_RE.match(statement_head(stmt)) and len(text) >= 20 and text in host_plain:
            dropped += 1
            continue
        kept.append(stmt)
    return '\n'.join(kept) if dropped else donor


def resolve_name_conflicts(host: str, donor: str) -> str:
    """Rename the donor's top-level functions/classes that clash with the
    host's. Two seeds declaring `function test()` or `class A` cannot share
    a file ("Cannot redeclare" is a compile-time fatal), so the donor's
    declaration and its references are renamed instead."""
    donor = _drop_verbatim_decls(host, donor)
    host_names = toplevel_names(host)
    conflicts = host_names & toplevel_names(donor)
    for name in sorted(conflicts, key=len, reverse=True):
        new_name, k = name + '_ff', 2
        while new_name in host_names or re.search(r'\b' + re.escape(new_name) + r'\b', host):
            new_name, k = f"{name}_ff{k}", k + 1
        # Not a variable, member, namespace part or quoted text...
        donor = re.sub(r'(?<![-\w$>.:\'"`\\])\b' + re.escape(name) + r'\b', new_name, donor)
        # ...except a string that is exactly the name: class_exists('Foo').
        donor = re.sub(r'([\'"])' + re.escape(name) + r'\1',
                       lambda m: m.group(1) + new_name + m.group(1), donor)
    return donor


# ---------------------------------------------------------------------------
# Program clean-up before fusion
# ---------------------------------------------------------------------------

# `declare(strict_types=1);` must be the first statement and `namespace X;`
# must precede all code, so both are hoisted to the top of a fused file.
# The block forms (`declare(ticks=1) { ... }`, `namespace X { ... }`) are
# legal where they stand and are left alone.
_PREAMBLE_RE = re.compile(r'^\s*(?:declare\s*\([^)]*\)\s*;|namespace\s+[\w\\]+\s*;)\s*(?:(?://|#(?!\[)).*)?$')

_GOTO_RE = re.compile(r'^\s*goto\s+\w+\s*;.*$', re.M)
_LABEL_RE = re.compile(r'^\s*\w+\s*:\s*$', re.M)
_REOPEN_RE = re.compile(r'<\?(?:php\b|=|(?![a-zA-Z]))')
_DONE_MARKER_RE = re.compile(r'(?:^|\n)[ \t]*(?:===DONE===|==DONE==|===Done===|Done)[ \t]*$')


def extract_preamble(code: str) -> Tuple[List[str], str]:
    """Split `code` into (preamble lines, rest)."""
    preamble, rest = [], []
    for line in code.splitlines():
        if _PREAMBLE_RE.match(line):
            preamble.append(line.strip())
        else:
            rest.append(line)
    return preamble, '\n'.join(rest)


def merge_preambles(*preambles: List[str]) -> List[str]:
    """At most one namespace statement survives (two are a fatal error),
    declares come first."""
    seen = list(dict.fromkeys(line for p in preambles for line in p))
    declares = [p for p in seen if p.startswith('declare')]
    namespaces = [p for p in seen if p.startswith('namespace')][:1]
    return declares + namespaces


def inline_html_to_echo(code: str) -> str:
    """Turn `?> html <?php` spans into `echo 'html';` so the program stays in
    PHP mode: a `?>` inside a try block would leave the block unclosed.
    Inline HTML is output, so echoing it keeps the behaviour."""
    if '?>' not in code:
        return code
    mask = lexical_mask(code)
    out, i, n = [], 0, len(code)
    while i < n:
        j = code.find('?>', i)
        while j != -1 and not mask[j]:
            j = code.find('?>', j + 2)
        if j == -1:
            out.append(code[i:])
            break
        out.append(code[i:j])
        m = _REOPEN_RE.search(code, j + 2)
        html = code[j + 2:m.start()] if m else code[j + 2:]
        if html.startswith('\n'):
            html = html[1:]               # PHP eats the newline after ?>
        if html.strip():
            out.append("\necho '" + html.replace('\\', '\\\\').replace("'", "\\'") + "';\n")
        else:
            out.append("\n")
        if m is None:
            break
        if m.group(0) == '<?=':
            i = m.end()
            k = code.find('?>', i)
            expr = code[i:k] if k != -1 else code[i:]
            out.append("echo " + expr.strip().rstrip(';') + ";")
            i = (k + 2) if k != -1 else n
            continue
        i = m.end()
    return ''.join(out)


def clean_php_code(code: str) -> str:
    """The FILE section of a seed as a plain statement list: no `<?php`/`?>`
    envelope, no trailing `===DONE===` marker, inline HTML echoed, and no
    goto/labels (a label from one seed dangles once its goto moves)."""
    s = _DONE_MARKER_RE.sub('', code.strip()).rstrip()
    if s.startswith('<?php'):
        s = s[5:].lstrip()
    elif s.startswith('<?'):
        s = s[2:].lstrip()
    if s.endswith('?>'):
        s = s[:-2].rstrip()
    s = inline_html_to_echo(s)
    s = _GOTO_RE.sub('', s)
    s = _LABEL_RE.sub('', s)
    return '\n' + s + '\n'


# ---------------------------------------------------------------------------
# Wrapping the fused body
# ---------------------------------------------------------------------------

_INCLUDE_RE = re.compile(r"\b(include|require)(_once)?(\s*\(?\s*)([^;\n]+?)(\s*\)?\s*;)")


def once_repeated_includes(body: str) -> str:
    """Turn every include of a path included more than once into
    include_once: a helper file included twice declares its classes twice."""
    def key(m):
        return re.sub(r"\s+", "", m.group(4))
    counts = collections.Counter(key(m) for m in _INCLUDE_RE.finditer(body))
    repeated = {k for k, c in counts.items() if c > 1}
    if not repeated:
        return body
    return _INCLUDE_RE.sub(
        lambda m: (m.group(1) + "_once" + m.group(3) + m.group(4) + m.group(5))
        if key(m) in repeated else m.group(0), body)


_BRACED_NS_RE = re.compile(r'^namespace\b\s*(?:[\w\\]+\s*)?\{')


def has_braced_namespace(code: str) -> bool:
    return any(_BRACED_NS_RE.match(statement_head(s)) for s in split_statements(code))


def wrap_body(body: str) -> str:
    """Wrap each run of ordinary statements in try/catch so an exception in
    one half does not end the program, while declarations and statements
    PHP only accepts at the outermost scope stay there (early binding,
    `use`, `const`).

    A file with a braced `namespace X { }` block may hold no code outside
    namespace blocks, so in that case every other run of statements goes
    into a global `namespace { }` block."""
    body = once_repeated_includes(body)
    stmts = split_statements(body)
    if not stmts:
        return body
    if not any(_BRACED_NS_RE.match(statement_head(s)) for s in stmts):
        return _wrap_statements(stmts)
    out, group = [], []
    for stmt in stmts:
        if _BRACED_NS_RE.match(statement_head(stmt)):
            if group:
                out.append("namespace {\n" + _wrap_statements(group) + "\n}")
                group = []
            out.append(stmt)
        else:
            group.append(stmt)
    if group:
        out.append("namespace {\n" + _wrap_statements(group) + "\n}")
    return "\n".join(out)


def _wrap_statements(stmts: List[str]) -> str:
    out, run = [], []

    def flush():
        if run:
            out.append("try {\n" + "\n".join(run) + "\n} catch (\\Throwable $_ff_e) {}")
            run.clear()

    for stmt in stmts:
        if TOPLEVEL_ONLY_RE.match(statement_head(stmt)):
            flush()
            out.append(stmt)
        else:
            run.append(stmt)
    flush()
    return "\n".join(out)


# ---------------------------------------------------------------------------
# Renaming
# ---------------------------------------------------------------------------

def php_variables(code: str) -> List[str]:
    """Distinct renamable variables of `code`, in order of appearance."""
    return [v for v in dict.fromkeys(PHP_VAR_RE.findall(code)) if v not in UNRENAMABLE_VARS]


def var_occurrences(code: str, var: str):
    """Match objects of `var` in `code` that are the whole variable name
    (`$a` must not match the start of `$array`)."""
    return list(re.finditer(re.escape(var) + r'(?![A-Za-z0-9_\x80-\xff])', code))
