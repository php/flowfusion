"""
Seeds: loading, analysis and scheduling.

A seed is the --FILE-- section of a .phpt test plus the sections fusion
carries over (INI, EXTENSION) and the php-src directory it came from, whose
fixtures the program may open via `__DIR__`. Seeds come from php-src's own
test suite, php-langspec, and programs translated into PHP from other
languages' bug corpora (seeds/translated/).
"""

import ast
import json
import os
import random
import re
import sqlite3
import threading
from dataclasses import dataclass, field
from typing import Dict, List, Optional, Sequence

from . import phplex

SECTION_RE = re.compile(r'^--([A-Z_]+)--[ \t]*\r?$', re.M)


def parse_phpt(text: str) -> Dict[str, str]:
    """Sections of a .phpt file. A section header is a line that is
    exactly `--NAME--` (run-tests.php uses the same anchored form; a
    looser match cut PEM blocks such as `-----END KEY-----` in half)."""
    sections = {}
    matches = list(SECTION_RE.finditer(text))
    for k, m in enumerate(matches):
        end = matches[k + 1].start() if k + 1 < len(matches) else len(text)
        body = text[m.end():end]
        if body.startswith('\n'):
            body = body[1:]
        sections.setdefault(m.group(1), body.rstrip('\n'))
    return sections


def dataflow_groups(code: str) -> List[List[str]]:
    """Coarse dataflow: variables that appear on the same line are
    connected; the groups are the connected components (FlowFusion's
    "dataflow" — sound enough for fuzzing, and cheap)."""
    parent: Dict[str, str] = {}

    def find(x):
        while parent[x] != x:
            parent[x] = parent[parent[x]]
            x = parent[x]
        return x

    for line in code.split('\n'):
        names = list(dict.fromkeys(phplex.PHP_VAR_RE.findall(line)))
        for v in names:
            parent.setdefault(v, v)
        for v in names[1:]:
            ra, rb = find(names[0]), find(v)
            if ra != rb:
                parent[rb] = ra
    groups: Dict[str, List[str]] = {}
    for v in parent:
        groups.setdefault(find(v), []).append(v)
    return list(groups.values())


@dataclass
class Seed:
    name: str
    code: str                            # raw --FILE-- section
    ini: str = ""
    extension: str = ""
    origin: str = "php-src"              # php-src | langspec | translated | fused
    source_dirs: tuple = ()              # fixture directories (relative to phpt_deps)
    dataflows: Optional[List[List[str]]] = None
    has_decl: bool = False
    description: str = ""
    _clean: Optional[str] = field(default=None, repr=False)

    @property
    def clean(self) -> str:
        """The code without the `<?php` envelope and inline HTML, computed
        once per seed (seeds are fused thousands of times)."""
        if self._clean is None:
            self._clean = phplex.clean_php_code(self.code)
        return self._clean


# Seeds that call zend_test helpers breaking engine invariants on purpose:
# fusion separates the break from its repair and the "crash" says nothing
# about PHP (the reference implementation's first false finding).
EXCLUDE_RE = re.compile(
    r'\bzend_(?:create_unterminated_string|test_crash|leak_bytes|leak_variable|'
    r'terminate_string|test_array_return)\s*\(|(?<![A-Za-z])_?ZendTestFiber\b')

DECL_RE = re.compile(r'\b(?:class|interface|trait)\s+[A-Za-z_]\w*')

SCHEMA = """
CREATE TABLE IF NOT EXISTS seeds (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE,
    origin TEXT,
    source_dir TEXT,
    description TEXT,
    phpcode TEXT,
    configuration TEXT,
    extension TEXT,
    dataflow TEXT,
    has_decl INTEGER,
    lines INTEGER,
    valid INTEGER DEFAULT 1,
    note TEXT
)
"""


def seed_record(name: str, phpt_text: str, origin: str, source_dir: Optional[str]):
    """Row for the seed DB, or None when the test has no program or is
    excluded."""
    sec = parse_phpt(phpt_text)
    code = sec.get('FILE') or sec.get('FILEEOF') or ''
    if not code.strip() or EXCLUDE_RE.search(code):
        return None
    flows = dataflow_groups(phplex.strip_comments(code))
    return (name, origin, source_dir, sec.get('TEST', '').strip()[:200], code,
            sec.get('INI', ''), sec.get('EXTENSIONS', sec.get('EXTENSION', '')),
            json.dumps(flows), int(bool(DECL_RE.search(code))), code.count('\n') + 1)


def write_seed_db(db_path: str, records: Sequence[tuple]) -> int:
    if os.path.exists(db_path):
        os.remove(db_path)
    conn = sqlite3.connect(db_path)
    conn.execute(SCHEMA)
    conn.executemany(
        "INSERT OR IGNORE INTO seeds (name, origin, source_dir, description, phpcode, configuration,"
        " extension, dataflow, has_decl, lines) VALUES (?,?,?,?,?,?,?,?,?,?)", records)
    conn.commit()
    n = conn.execute("SELECT COUNT(*) FROM seeds").fetchone()[0]
    conn.close()
    return n


def mark_invalid(db_path: str, names_notes: Sequence[tuple]):
    conn = sqlite3.connect(db_path)
    conn.executemany("UPDATE seeds SET valid = 0, note = ? WHERE name = ?",
                     [(note, name) for name, note in names_notes])
    conn.commit()
    conn.close()


def load_seeds(db_path: str) -> List[Seed]:
    """All valid seeds of `db_path`. Understands the new schema and the
    one written by FlowFusion 1.x (str()-encoded lists, no origins)."""
    conn = sqlite3.connect(db_path)
    cols = {r[1] for r in conn.execute("PRAGMA table_info(seeds)")}
    seeds = []
    if 'name' in cols:
        rows = conn.execute(
            "SELECT name, origin, source_dir, description, phpcode, configuration, extension,"
            " dataflow, has_decl FROM seeds WHERE valid = 1").fetchall()
        for name, origin, sdir, desc, code, ini, ext, flows, has_decl in rows:
            seeds.append(Seed(name=name, code=code, ini=ini or "", extension=ext or "",
                              origin=origin or "php-src",
                              source_dirs=(sdir,) if sdir else (),
                              dataflows=json.loads(flows) if flows else None,
                              has_decl=bool(has_decl), description=desc or ""))
    else:
        rows = conn.execute(
            "SELECT id, phpcode, dataflow, description, configuration, extension FROM seeds").fetchall()
        for rid, code, flows, desc, ini, ext in rows:
            try:
                groups = ast.literal_eval(flows) if flows else None
            except (ValueError, SyntaxError):
                groups = None
            seeds.append(Seed(name=f"seed{rid}", code=code, ini=ini or "", extension=ext or "",
                              dataflows=groups, has_decl=bool(DECL_RE.search(code or "")),
                              description=desc or ""))
    conn.close()
    return seeds


def without_missing_extensions(seeds: List[Seed], available: Optional[set]):
    """(kept, dropped): seeds whose --EXTENSIONS-- are all compiled in.
    A child inherits its parents' extension requirements, and run-tests.php
    skips it when one is missing — every fusion of such a seed is wasted."""
    if not available:
        return seeds, 0
    kept = [s for s in seeds
            if all(e.strip().lower() in available for e in s.extension.splitlines() if e.strip())]
    return kept, len(seeds) - len(kept)


class SeedPool:
    """Uniform seed selection with a penalty for seeds whose children
    almost never parse: a seed that poisons every fusion it takes part in
    is drawn a tenth as often once that is established."""

    MIN_USES = 8
    BAD_RATIO = 0.9
    PENALTY = 0.1

    def __init__(self, seeds: List[Seed], rng=None, translated_weight: float = 1.0):
        self.seeds = seeds
        self.rng = rng or random.Random()
        self.translated_weight = translated_weight
        self.uses = [0] * len(seeds)
        self.bad = [0] * len(seeds)
        self.index = {s.name: i for i, s in enumerate(seeds)}
        self._lock = threading.Lock()
        self.decl_seeds = [i for i, s in enumerate(seeds) if s.has_decl]

    def __len__(self):
        return len(self.seeds)

    def _weight(self, i: int) -> float:
        w = self.translated_weight if self.seeds[i].origin == 'translated' else 1.0
        if self.uses[i] >= self.MIN_USES and self.bad[i] >= self.BAD_RATIO * self.uses[i]:
            w *= self.PENALTY
        return w

    def pick(self, with_decl: bool = False) -> Seed:
        pool = self.decl_seeds if (with_decl and self.decl_seeds) else None
        for _ in range(64):
            i = self.rng.choice(pool) if pool else self.rng.randrange(len(self.seeds))
            w = self._weight(i)
            if w >= 1.0 or self.rng.random() < w:
                return self.seeds[i]
        return self.seeds[i]

    def feedback(self, names, parsed: bool):
        """Record whether a child of these seeds parsed."""
        with self._lock:
            for name in names:
                i = self.index.get(name)
                if i is not None:
                    self.uses[i] += 1
                    if not parsed:
                        self.bad[i] += 1

    def penalised(self) -> int:
        return sum(1 for i in range(len(self.seeds))
                   if self.uses[i] >= self.MIN_USES and self.bad[i] >= self.BAD_RATIO * self.uses[i])
