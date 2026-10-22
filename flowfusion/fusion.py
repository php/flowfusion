"""
Program fusion strategies.

- Dataflow fusion (FlowFusion's original technique): connect a variable of
  seed A to a use of a variable in seed B, either through a `$fusion`
  bridge assigned at the end of A or by renaming B's variable to A's.
- State fusion: cut both seeds at an intermediate, variable-rich state and
  interleave A_prefix + B_prefix + A_suffix + B_suffix (see state.py).
- Declaration fusion: make a class/interface/trait declaration of the host
  depend on a declaration of the donor (`implements`, `use`, `extends`),
  or instantiate a donor class from the host.

Every strategy returns a FusedProgram: a statement list plus what the
.phpt envelope needs (preamble, INI, extensions, fixtures). Fused programs
can themselves be fused again with a third seed (chaining).
"""

import random
import re
from dataclasses import dataclass, field
from typing import List, Optional, Tuple

from . import phplex, state
from .corpus import Seed
from .mutator import Mutator


@dataclass
class FusedProgram:
    body: str
    preamble: List[str]
    parents: List[str]
    strategies: List[str]
    modes: List[str]
    ini: List[str] = field(default_factory=list)
    extensions: List[str] = field(default_factory=list)
    source_dirs: List[str] = field(default_factory=list)
    bridge_vars: List[str] = field(default_factory=list)

    def as_seed(self) -> Seed:
        """This program as a seed, so it can be fused again."""
        code = "\n".join(self.preamble) + "\n" + self.body
        return Seed(name="+".join(self.parents), code=code, ini="\n".join(self.ini),
                    extension="\n".join(self.extensions), origin="fused",
                    source_dirs=tuple(self.source_dirs), _clean=code,
                    has_decl=bool(re.search(r'\b(?:class|interface|trait)\s+\w', code)))


def _lines(text: str) -> List[str]:
    return [l.strip() for l in (text or "").splitlines() if l.strip()]


def pick_occurrence_count(rng) -> int:
    """How many uses of a variable a dataflow edge rewrites: usually one
    clean edge, rarely two or three."""
    return rng.choices((1, 2, 3), weights=(89, 10, 1))[0]


class Strategy:
    name = "base"
    tag = "// fusion"

    def __init__(self, rng=None, mutator: Optional[Mutator] = None):
        self.rng = rng or random.Random()
        self.mutator = mutator

    def viable(self, host: Seed, donor: Seed) -> bool:
        return True

    def prepare(self, host: Seed, donor: Seed) -> Tuple[str, str, List[str]]:
        """(host code, donor code, preamble): envelope stripped, mutated,
        `declare`/`namespace` hoisted, donor's clashing declarations
        renamed."""
        a, b = host.clean, donor.clean
        if self.mutator:
            a, b = self.mutator.mutate(a), self.mutator.mutate(b)
        pre_a, a = phplex.extract_preamble(a)
        pre_b, b = phplex.extract_preamble(b)
        b = phplex.resolve_name_conflicts(a, b)
        return a, b, phplex.merge_preambles(pre_a, pre_b)

    def result(self, host: Seed, donor: Seed, body: str, preamble: List[str],
               mode: str, bridge_vars=()) -> FusedProgram:
        dirs = list(dict.fromkeys(list(host.source_dirs) + list(donor.source_dirs)))
        exts = list(dict.fromkeys(_lines(host.extension) + _lines(donor.extension)))
        return FusedProgram(body=body, preamble=preamble, parents=[host.name, donor.name],
                            strategies=[self.name], modes=[mode],
                            ini=_lines(host.ini) + _lines(donor.ini),
                            extensions=exts, source_dirs=dirs, bridge_vars=list(bridge_vars))

    def fuse(self, host: Seed, donor: Seed) -> FusedProgram:
        raise NotImplementedError


def _tag_changed_lines(before: str, after: str, tag: str) -> str:
    old, new = before.split('\n'), after.split('\n')
    if len(old) != len(new):
        return after
    return '\n'.join(state.tag_line(n, tag) if o != n and tag not in n else n
                     for o, n in zip(old, new))


class DataflowFusion(Strategy):
    name = "dataflow"
    tag = "// dataflow fusion"

    def _pick_group(self, seed: Seed, code: str) -> List[str]:
        present = set(phplex.php_variables(code))
        groups = [[v for v in g if v in present] for g in (seed.dataflows or [])]
        groups = [g for g in groups if g]
        if not groups:
            return sorted(present)
        if self.rng.random() < 0.5:
            return self.rng.choice(groups)
        return max(groups, key=len)        # the most connected cluster

    def _rewrite_uses(self, code: str, var: str, new: str) -> str:
        occ = phplex.var_occurrences(code, var)
        if not occ:
            return code
        n = min(pick_occurrence_count(self.rng), len(occ))
        for m in sorted(self.rng.sample(occ, n), key=lambda m: m.start(), reverse=True):
            code = code[:m.start()] + new + code[m.end():]
        return code

    # Literals in code position: quoted strings without interpolation,
    # integers and floats (not array keys before `=>`).
    _LITERAL_RE = re.compile(r"""'(?:[^'\\\n]|\\.){0,40}'|"(?:[^"\\$\n]|\\.){0,40}"|(?<![\w$.])\d+(?:\.\d+)?(?![\w.])""")

    def _literal_edge(self, code: str, bridge: str) -> Optional[str]:
        """Replace one literal of `code` with `bridge`, for programs that
        have no variable to connect: `str_repeat("x", 3)` becomes
        `str_repeat($fusion, 3)`."""
        spans = phplex.code_spans(code)
        string_spans = {(i, j) for i, j in spans if code[i] in "'\""}
        mask = phplex.lexical_mask(code)
        cands = []
        for m in self._LITERAL_RE.finditer(code):
            if m.group(0)[0] in "'\"":
                ok = (m.start(), m.end()) in string_spans    # a whole string literal
            else:
                ok = mask[m.start()]                          # a number in code
            after = code[m.end():m.end() + 4].lstrip()
            if ok and not after.startswith(('=>', ':')):
                cands.append(m)
        if not cands:
            return None
        m = self.rng.choice(cands)
        return code[:m.start()] + bridge + code[m.end():]

    def fuse(self, host: Seed, donor: Seed) -> FusedProgram:
        a, b, preamble = self.prepare(host, donor)
        group_a = self._pick_group(host, a)
        group_b = self._pick_group(donor, b)
        if not group_a and group_b:
            # Only the donor has variables: let it be the source instead.
            host, donor, a, b = donor, host, b, a
            b = phplex.resolve_name_conflicts(a, b)
            group_a, group_b = group_b, []
        if not group_a:
            return self.result(host, donor, a + "\n" + b, preamble, "none")
        if not group_b:
            var_a = self.rng.choice(group_a)
            bridge = "$fusion" if "$fusion" not in a else f"$fusion{self.rng.randint(2, 999)}"
            new_b = self._literal_edge(b, bridge)
            if new_b is None:
                return self.result(host, donor, a + "\n" + b, preamble, "none")
            a = a.rstrip() + f"\n{bridge} = {var_a};  {self.tag}\n"
            new_b = _tag_changed_lines(b, new_b, self.tag)
            return self.result(host, donor, a + "\n" + new_b, preamble, "literal", [bridge])
        var_a = self.rng.choice(group_a)
        var_b = self.rng.choice(group_b)
        if var_a == var_b:
            # Common names ($a, $i, $obj) are shared by many seeds; renaming
            # one to itself would make no edge at all.
            others = [v for v in group_b if v != var_a] or \
                     [v for v in phplex.php_variables(b) if v != var_a]
            if others:
                var_b = self.rng.choice(others)
        rename = var_a != var_b and self.rng.random() < 0.5
        if not rename:
            # FlowFusion's bridge: A's value flows into B through $fusion.
            bridge = "$fusion"
            if bridge in b or bridge in a:
                bridge = f"$fusion{self.rng.randint(2, 999)}"
            a = a.rstrip() + f"\n{bridge} = {var_a};  {self.tag}\n"
            new_b = self._rewrite_uses(b, var_b, bridge)
            mode, bridges = "bridge", [bridge]
        else:
            # Rename: B now reads (and writes) A's variable directly, so
            # the two programs alias the same value.
            new_b = self._rewrite_uses(b, var_b, var_a)
            mode, bridges = "rename", []
        new_b = _tag_changed_lines(b, new_b, self.tag)
        return self.result(host, donor, a + "\n" + new_b, preamble, mode, bridges)


class StateFusion(Strategy):
    name = "state"
    tag = "// state fusion"

    def fuse(self, host: Seed, donor: Seed) -> FusedProgram:
        a, b, preamble = self.prepare(host, donor)
        body, info = state.interleave(a, b, rng=self.rng, tag=self.tag)
        a_lines, b_lines = a.count('\n'), b.count('\n')
        # How deep into each program the cut went (start / mid / end).
        def where(cut, total):
            if cut <= 1:
                return "s"
            return "e" if cut >= total - 1 else "m"
        mode = f"seg{where(info['a_cut'], a_lines)}{where(info['b_cut'], b_lines)}"
        return self.result(host, donor, body, preamble, mode)


_CLASS_HEADER_RE = re.compile(
    r'(?<![\w$>:\\])(?P<kind>class|interface|trait)\s+(?P<name>[A-Za-z_]\w*)'
    r'(?P<extends>\s+extends\s+[A-Za-z_\\][\w\\,\s]*?)?'
    r'(?P<implements>\s+implements\s+[A-Za-z_\\][\w\\,\s]*?)?'
    r'\s*\{')
_PLAIN_CLASS_RE = re.compile(r'(?m)^[ \t]*(?:final\s+)?class\s+(?P<name>[A-Za-z_]\w*)\s*\{')
_CTOR_RE = re.compile(r'function\s+__construct\s*\(([^)]*)\)')
_PUBLIC_METHOD_RE = re.compile(
    r'(?m)^[ \t]*(?:public\s+)?function\s+(?!__)(?P<name>[A-Za-z_]\w*)\s*\((?P<params>[^)]*)\)')


def _all_defaulted(params: str) -> bool:
    params = params.strip()
    return not params or all('=' in p or p.strip().startswith('...') for p in params.split(','))


class DeclarationFusion(Strategy):
    name = "declaration"
    tag = "// declaration fusion"

    def _headers(self, code: str):
        mask = phplex.lexical_mask(code)
        return [m for m in _CLASS_HEADER_RE.finditer(code) if mask[m.start()]]

    def _instantiable(self, code: str):
        """(class, method) for plain classes constructible without
        arguments; method is a public non-static method callable without
        arguments, or None."""
        out = []
        for m in _PLAIN_CLASS_RE.finditer(code):
            rest = code[m.end():]
            nxt = re.search(r'(?m)^[ \t]*(?:abstract\s+|final\s+)*(?:class|interface|trait|enum)\s', rest)
            body = rest[:nxt.start()] if nxt else rest
            ctor = _CTOR_RE.search(body)
            if ctor and not _all_defaulted(ctor.group(1)):
                continue
            method = None
            for mm in _PUBLIC_METHOD_RE.finditer(body):
                line = body[body.rfind('\n', 0, mm.start()) + 1:mm.end()]
                if re.search(r'\b(?:static|private|protected|abstract)\b', line):
                    continue
                if _all_defaulted(mm.group('params')):
                    method = mm.group('name')
                    break
            out.append((m.group('name'), method))
        return out

    def _direction_viable(self, host: Seed, donor: Seed) -> bool:
        if not donor.has_decl:
            return False
        return host.has_decl or bool(self._instantiable(donor.clean))

    def viable(self, a: Seed, b: Seed) -> bool:
        return self._direction_viable(a, b) or self._direction_viable(b, a)

    # -- injection primitives -------------------------------------------

    def _tag_header(self, code: str, at: int) -> str:
        nl = code.find('\n', at)
        return code + f"  {self.tag}" if nl == -1 else code[:nl] + f"  {self.tag}" + code[nl:]

    def _base_ref(self, code: str, donor_name: str):
        """`implements Donor` on a host class (`extends` on an interface)."""
        cands = [m for m in self._headers(code) if m.group('kind') != 'trait']
        if not cands:
            return None
        m = self.rng.choice(cands)
        brace = m.end() - 1
        is_iface = m.group('kind') == 'interface'
        has_list = m.group('extends') if is_iface else m.group('implements')
        kw = 'extends' if is_iface else 'implements'
        insert = f", {donor_name} " if has_list else f" {kw} {donor_name} "
        return self._tag_header(code[:brace].rstrip() + insert + code[brace:], m.start())

    def _trait_use(self, code: str, donor_name: str):
        """`use Donor;` as the first statement of a host class or trait."""
        cands = [m for m in self._headers(code) if m.group('kind') in ('class', 'trait')]
        if not cands:
            return None
        m = self.rng.choice(cands)
        at = m.end()
        return code[:at] + f"\n    use {donor_name};  {self.tag}\n" + code[at:]

    def _extends(self, code: str, donor_name: str):
        """`class Host extends Donor` on a host class without a parent."""
        cands = [m for m in self._headers(code)
                 if m.group('kind') == 'class' and not m.group('extends')]
        if not cands:
            return None
        m = self.rng.choice(cands)
        at = m.end('name')
        return self._tag_header(code[:at] + f" extends {donor_name}" + code[at:], m.start())

    def _instantiate(self, host_code: str, donor_code: str):
        cands = self._instantiable(donor_code)
        if not cands:
            return None
        name, method = self.rng.choice(cands)
        var = "$ff_obj"
        stmts = [f"{var} = new {name}();  {self.tag}", f"var_dump({var});"]
        if method:
            stmts.append(f"var_dump({var}->{method}());")
        return host_code.rstrip() + "\n" + "\n".join(stmts) + "\n"

    def fuse(self, a: Seed, b: Seed) -> FusedProgram:
        host, donor = a, b
        if not self._direction_viable(a, b) and self._direction_viable(b, a):
            host, donor = b, a
        h, d, preamble = self.prepare(host, donor)
        decls = [(m.group('kind'), m.group('name')) for m in self._headers(d)]
        fused, technique = None, "none"
        if decls:
            kind, name = self.rng.choice(decls)
            # The slot follows the donor's kind (interface -> implements,
            # trait -> use, class -> extends) so the dependency resolves and
            # the program runs past its declarations; a mismatched slot is
            # still drawn 20% of the time for the compile-time error paths.
            natural = {'interface': '_base_ref', 'trait': '_trait_use', 'class': '_extends'}[kind]
            order = ['_base_ref', '_trait_use', '_extends']
            if self.rng.random() < 0.8:
                order = [natural] + [o for o in order if o != natural]
            else:
                self.rng.shuffle(order)
            for prim in order:
                fused = getattr(self, prim)(h, name)
                if fused is not None:
                    technique = prim.strip('_')
                    break
        if fused is None:
            fused = self._instantiate(h, d)
            technique = "instantiate" if fused is not None else "none"
        if fused is None:
            fused = h
        # Donor declarations first: PHP resolves extends/implements/use when
        # the class statement executes, top to bottom.
        prog = self.result(host, donor, d + "\n" + fused, preamble, technique)
        prog.parents = [a.name, b.name]
        return prog


STRATEGIES = {cls.name: cls for cls in (DataflowFusion, StateFusion, DeclarationFusion)}


class Fuser:
    """Draws a strategy (or a chain of them over three seeds) per test."""

    def __init__(self, names: List[str], rng=None, mutator: Optional[Mutator] = None,
                 chain_rate: float = 0.15, weights: Optional[dict] = None):
        self.rng = rng or random.Random()
        self.strategies = [STRATEGIES[n](rng=self.rng, mutator=mutator) for n in names]
        self.chain_rate = chain_rate
        self.weights = [float((weights or {}).get(s.name, 1.0)) for s in self.strategies]

    def _pick(self, a: Seed, b: Seed) -> Strategy:
        usable = [(s, w) for s, w in zip(self.strategies, self.weights) if s.viable(a, b)]
        if not usable:
            usable = [(s, w) for s, w in zip(self.strategies, self.weights) if s.name != "declaration"]
        if not usable:
            usable = list(zip(self.strategies, self.weights))
        return self.rng.choices([s for s, _ in usable], weights=[w for _, w in usable], k=1)[0]

    def fuse(self, pick_seed) -> FusedProgram:
        """Fuse two seeds drawn with `pick_seed(with_decl=...)` and, with
        probability chain_rate, the result with a third one."""
        want_decl = (len(self.strategies) == 1 and self.strategies[0].name == "declaration") or \
            (any(s.name == "declaration" for s in self.strategies) and self.rng.random() < 0.15)
        a = pick_seed(with_decl=want_decl)
        b = pick_seed(with_decl=want_decl)
        strat = self._pick(a, b)
        prog = strat.fuse(a, b)
        if self.chain_rate and self.rng.random() < self.chain_rate:
            c = pick_seed(with_decl=False)
            host = prog.as_seed()
            second = self._pick(host, c)
            nxt = second.fuse(host, c)
            nxt.parents = prog.parents + [c.name]
            nxt.strategies = prog.strategies + nxt.strategies
            nxt.modes = prog.modes + nxt.modes
            nxt.bridge_vars = prog.bridge_vars + nxt.bridge_vars
            nxt.preamble = phplex.merge_preambles(prog.preamble, nxt.preamble)
            prog = nxt
        return prog
