"""
State analysis for state fusion.

A *state* is the program point right after a given line has executed.
State fusion cuts each parent at an intermediate state and interleaves the
four pieces — A_prefix + B_prefix + A_suffix + B_suffix — so that every
piece runs on top of state the other program produced (the reference
design in fusion-fuzz's core/state_analysis.py).

Two questions decide where to cut:

1. Which states are *interesting*: the more variables are live at a point,
   the richer the state the other program's continuation runs into. Points
   are sampled proportionally to their live-variable count; when no line
   has any, a triangular weight prefers the middle of the program.
2. Where the text *can* be cut: only between two complete top-level
   statements (bracket depth zero, not inside a string or heredoc, not
   mid-expression, not between `if` and `else`, not inside an
   alternative-syntax block). A drawn point snaps to the nearest legal cut
   at or after it, so the state it represents is in the prefix.
"""

import random
import re
from dataclasses import dataclass
from typing import List, Optional, Sequence

from .phplex import lexical_mask, masked_text


@dataclass
class StatePoint:
    line_idx: int          # 0-based line whose end is the state
    live: int = 0          # live variables at that point (sampling weight)


_DECLARE_RES = [
    re.compile(r'\$([A-Za-z_\x80-\xff][\w\x80-\xff]*)\s*(?:\[[^\]]*\]\s*)?(?:[-+*/.%&|^]|\?\?|<<|>>|\*\*)?=(?![=>])'),
    re.compile(r'\bas\s+&?\$([A-Za-z_\x80-\xff][\w\x80-\xff]*)'),
    re.compile(r'=>\s*&?\$([A-Za-z_\x80-\xff][\w\x80-\xff]*)\s*\)'),
    re.compile(r'\bglobal\s+\$([A-Za-z_\x80-\xff][\w\x80-\xff]*)'),
    re.compile(r'\bstatic\s+\$([A-Za-z_\x80-\xff][\w\x80-\xff]*)'),
]
_UNSET_RE = re.compile(r'\bunset\s*\(([^)]*)\)')
_FUNC_RE = re.compile(r'\bfunction\b')
_BRACE_RE = re.compile(r'[{}]')


def live_counts(lines: Sequence[str], masked_lines: Sequence[str]) -> List[int]:
    """Live-variable count after each line.

    PHP scopes variables to functions only, so the model is a stack of
    function scopes: a `{` opened on a line containing `function` starts a
    fresh scope and its `}` restores the enclosing one; every other block
    shares its function's scope."""
    frames = [(True, set())]           # (is_function_scope, live set)
    pending_func = False
    counts = []
    for line, code in zip(lines, masked_lines):
        live = frames[-1][1]
        for m in _UNSET_RE.finditer(code):
            for v in re.findall(r'\$(\w+)', m.group(1)):
                live.discard(v)
        for rx in _DECLARE_RES:
            for m in rx.finditer(code):
                # the name must start in real code, not in a string
                live.add(m.group(1))
        counts.append(len(live))
        if _FUNC_RE.search(code):
            pending_func = True
        for b in _BRACE_RE.finditer(code):
            if b.group(0) == '{':
                if pending_func:
                    frames.append((True, set()))
                    pending_func = False
                else:
                    frames.append((False, frames[-1][1]))
            elif len(frames) > 1:
                frames.pop()
        if code.rstrip().endswith(';'):
            pending_func = False       # `$f = function() use ($x);` never opened
    return counts


# Keywords that continue the statement on the previous line.
_CONTINUATION_RE = re.compile(
    r'^\s*(?:\}\s*)?(?:else\b|elseif\b|catch\b|finally\b)'
    r'|^\s*\}?\s*while\s*\(.*\)\s*;\s*$'          # the tail of a do/while
    r'|^\s*\{'                                      # Allman-style brace
    r'|^\s*(?:->|\?->|::|\.|\+|-(?!-)|\*|/|\?|:|&&|\|\||and\b|or\b|xor\b|\))')
_ALT_OPEN_RE = re.compile(r'\b(?:if|elseif|foreach|for|while|switch|declare)\s*\(.*\)\s*:(?!:)')
_ALT_ELSE_RE = re.compile(r'\belse\s*:(?!:)')
_ALT_CLOSE_RE = re.compile(r'\bend(?:if|foreach|for|while|switch|declare)\b')
_ATTRIBUTE_RE = re.compile(r'^\s*#\[')


def segment_cuts(code: str) -> List[int]:
    """Legal cut positions s (prefix = lines[:s]) of `code`. 0 and len(lines)
    are always legal, so a split never fails."""
    lines = code.splitlines()
    n = len(lines)
    if n == 0:
        return [0]
    mask = lexical_mask(code)
    masked = masked_text(code).splitlines()
    if len(masked) != n:          # pathological line endings; cut nowhere
        return [0, n]

    cuts = [0]
    depth = 0
    alt_depth = 0
    off = 0
    last_code_char = ''
    for i, (line, mline) in enumerate(zip(lines, masked)):
        for ch in mline:
            if ch in '([{':
                depth += 1
            elif ch in ')]}':
                depth -= 1
        alt_depth += len(_ALT_OPEN_RE.findall(mline))
        alt_depth -= len(_ALT_CLOSE_RE.findall(mline))
        alt_depth = max(alt_depth, 0)
        stripped = mline.strip()
        if stripped:
            last_code_char = stripped[-1]
        end = off + len(line)
        off = end + 1
        if i == n - 1:
            break
        if depth != 0 or alt_depth != 0:
            continue
        # The newline ending this line lies inside a string, heredoc or
        # block comment: cutting here would split the literal.
        if end < len(mask) and not mask[end]:
            continue
        if last_code_char not in (';', '}', ''):
            continue
        if _ATTRIBUTE_RE.match(line):
            continue
        nxt = next((l for l in masked[i + 1:] if l.strip()), None)
        if nxt is not None and (_CONTINUATION_RE.match(nxt) or _ALT_ELSE_RE.match(nxt.strip())):
            continue
        cuts.append(i + 1)
    cuts.append(n)
    return sorted(set(cuts))


def _center_weights(n: int) -> List[int]:
    return [min(i + 1, n - i) for i in range(n)]


def find_state_points(code: str) -> List[StatePoint]:
    lines = code.splitlines()
    if not lines:
        return []
    masked = masked_text(code).splitlines()
    if len(masked) != len(lines):
        masked = lines
    counts = live_counts(lines, masked)
    return [StatePoint(i, c) for i, c in enumerate(counts) if lines[i].strip()] or \
           [StatePoint(i, c) for i, c in enumerate(counts)]


def pick_state_point(code: str, rng=random) -> Optional[StatePoint]:
    """One state point, drawn proportionally to its live-variable count."""
    points = find_state_points(code)
    if not points:
        return None
    weights = [p.live for p in points]
    if not any(weights):
        weights = _center_weights(len(points))
    return rng.choices(points, weights=weights, k=1)[0]


def split_at_point(code: str, point: Optional[StatePoint]):
    """(prefix_lines, suffix_lines, cut) — the cut is the first legal one at
    or after the point (the point's own line stays in the prefix)."""
    lines = code.splitlines()
    cuts = segment_cuts(code)
    # Interior cuts only, when there are any: live counts grow toward the
    # end of a program, so the end is drawn often, and a cut there (or at
    # the very start) makes the interleave a plain concatenation.
    interior = [c for c in cuts if 0 < c < len(lines)
                and any(l.strip() for l in lines[:c]) and any(l.strip() for l in lines[c:])]
    cuts = interior or cuts
    target = (point.line_idx + 1) if point else len(lines)
    after = [c for c in cuts if c >= target]
    cut = after[0] if after else cuts[-1]
    return lines[:cut], lines[cut:], cut


def tag_line(line: str, tag: str) -> str:
    """Append a fusion tag comment to `line` — or put it on its own line
    before it where a trailing comment would change the code (a heredoc
    opener, or a line whose end is inside a string)."""
    if re.search(r'<<<[ \t]*[\'"]?\w+[\'"]?\s*$', line) or line.count('"') % 2 or line.count("'") % 2:
        indent = line[:len(line) - len(line.lstrip())]
        return f"{indent}{tag}\n{line}"
    return f"{line}  {tag}"


def tag_segment(seg: List[str], tag: Optional[str]) -> List[str]:
    if not tag or not seg:
        return list(seg)
    seg = list(seg)
    idx = next((i for i, l in enumerate(seg) if l.strip()), None)
    if idx is not None:
        seg[idx] = tag_line(seg[idx], tag)
    return seg


def interleave(a_code: str, b_code: str, rng=random, tag: Optional[str] = None):
    """Four-segment state fusion of `a_code` and `b_code`.

    Returns (fused_code, info) where info records the cut lines and live
    counts of the chosen points."""
    pa = pick_state_point(a_code, rng)
    pb = pick_state_point(b_code, rng)
    a_pre, a_suf, a_cut = split_at_point(a_code, pa)
    b_pre, b_suf, b_cut = split_at_point(b_code, pb)
    fused = a_pre + tag_segment(b_pre, tag) + a_suf + tag_segment(b_suf, tag)
    info = {
        "a_cut": a_cut, "b_cut": b_cut,
        "a_live": pa.live if pa else 0, "b_live": pb.live if pb else 0,
    }
    return "\n".join(fused), info
