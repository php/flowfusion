"""
Terminal user interface.

`Dashboard` redraws a full-screen status view (ANSI escapes only, no
dependencies) about once per second: throughput, outcome rates per fusion
strategy and execution mode, running batches, recent bugs and a log tail.
`PlainReporter` prints one status line at an interval and every bug as it
is found — for nohup/CI or when stdout is not a terminal.
"""

import os
import re
import shutil
import sys
import threading
import time

_ANSI_RE = re.compile(r'\x1b\[[0-9;?]*[A-Za-z]')


def _fmt_dur(sec: float) -> str:
    sec = int(sec)
    d, rem = divmod(sec, 86400)
    h, rem = divmod(rem, 3600)
    m, s = divmod(rem, 60)
    return (f"{d}d " if d else "") + f"{h:02d}:{m:02d}:{s:02d}"


def _pct(n, d) -> str:
    return f"{100.0 * n / d:5.1f}%" if d else "    - "


class Style:
    def __init__(self, enabled: bool):
        self.on = enabled

    def __call__(self, text, *codes):
        if not self.on or not codes:
            return str(text)
        return f"\x1b[{';'.join(codes)}m{text}\x1b[0m"

    bold, dim, red, green, yellow, cyan, magenta = "1", "2", "31", "32", "33", "36", "35"


def visible_len(s: str) -> int:
    return len(_ANSI_RE.sub('', s))


def clip(s: str, width: int) -> str:
    """Truncate to `width` visible characters, keeping escape codes."""
    if visible_len(s) <= width:
        return s
    out, n, i = [], 0, 0
    while i < len(s) and n < width - 1:
        m = _ANSI_RE.match(s, i)
        if m:
            out.append(m.group(0))
            i = m.end()
            continue
        out.append(s[i])
        n += 1
        i += 1
    return "".join(out) + "…\x1b[0m"


def _bar(frac: float, width: int, st: Style) -> str:
    frac = max(0.0, min(1.0, frac))
    full = int(frac * width)
    color = st.green if frac >= 0.8 else st.yellow if frac >= 0.5 else st.red
    return st("█" * full, color) + st("░" * (width - full), st.dim)


def _summary_line(snap) -> str:
    k = snap["kinds"]
    t = snap["tests"] or 1
    return (f"[{_fmt_dur(snap['elapsed'])}] tests {snap['tests']:,} ({snap['recent_rate']:.1f}/s) | "
            f"unique bugs {snap['info'].get('unique_bugs', 0)} (+{snap['new_signatures']} new) | "
            f"bug hits {snap['bugs']} | parse-ok {100.0 * (t - k.get('parse', 0)) / t:.1f}% | "
            f"timeouts {k.get('timeout', 0)}")


class Dashboard:
    def __init__(self, stats, interval: float = 1.0):
        self.stats = stats
        self.interval = interval
        self.st = Style(os.environ.get("NO_COLOR") is None)
        self._stop = threading.Event()
        self._thread = None

    def start(self):
        sys.stdout.write("\x1b[?1049h\x1b[?25l")
        sys.stdout.flush()
        self._thread = threading.Thread(target=self._loop, daemon=True, name="ui")
        self._thread.start()

    def stop(self):
        self._stop.set()
        if self._thread:
            self._thread.join(timeout=5)
        sys.stdout.write("\x1b[?25h\x1b[?1049l")
        sys.stdout.flush()

    def _loop(self):
        while not self._stop.is_set():
            try:
                self.draw()
            except Exception:          # the UI must never take the fuzzer down
                pass
            self._stop.wait(self.interval)

    def draw(self):
        cols, rows = shutil.get_terminal_size((100, 40))
        lines = self.render(self.stats.snapshot(), cols, rows)
        buf = "\x1b[H" + "".join(clip(l, cols) + "\x1b[K\n" for l in lines[:rows - 1]) + "\x1b[J"
        sys.stdout.write(buf)
        sys.stdout.flush()

    def render(self, snap, cols: int, rows: int):
        st = self.st
        info = snap["info"]
        k = snap["kinds"]
        tests = snap["tests"]
        rule = st("─" * max(10, cols - 1), st.dim)
        title = st(" FlowFusion ", st.bold, "7") + st("  PHP interpreter fuzzer", st.bold)
        clock = f"{_fmt_dur(snap['elapsed'])}"
        if info.get("stopping"):
            clock = st("stopping… ", st.yellow) + clock
        L = [title + " " * max(1, cols - visible_len(title) - len(clock) - 2) + st(clock, st.cyan), rule]

        def kv(label, value):
            return st(f"{label:<9}", st.dim) + value
        L.append(kv("target", info.get("target", "?")))
        L.append(kv("seeds", info.get("seeds", "?")))
        L.append(kv("harness", info.get("harness", "?")))
        L.append(kv("fusion", info.get("fusion", "?")))
        L.append(rule)

        # -- totals -------------------------------------------------------
        uniq = info.get("unique_bugs", 0)
        new = snap["new_signatures"]
        bugs_txt = st(f"{uniq}", st.bold, st.red if new else "0")
        if new:
            bugs_txt += st(f"  (+{new} this run)", st.red, st.bold)
        running = snap["running"]
        avg = st("(avg %.1f)" % snap['rate'], st.dim)
        L.append(f"{st('tests', st.dim)} {st(f'{tests:,}', st.bold):>12}   "
                 f"{st('speed', st.dim)} {snap['recent_rate']:6.1f}/s {avg}   "
                 f"{st('batches', st.dim)} {snap['batches']:,}  {st('running', st.dim)} {len(running)}")
        L.append(f"{st('unique bugs', st.dim)} {bugs_txt}   {st('bug hits', st.dim)} {snap['bugs']:,}   "
                 f"{st('timeouts', st.dim)} {k.get('timeout', 0):,}   {st('lost', st.dim)} {k.get('lost', 0):,}   "
                 f"{st('skipped', st.dim)} {k.get('skip', 0):,}")
        parsed = (tests - k.get('parse', 0)) / tests if tests else 0.0
        L.append(f"{st('parse-ok', st.dim)} {_bar(parsed, 30, st)} {parsed * 100:5.1f}%   "
                 f"{st('runs clean', st.dim)} {_pct(k.get('ok', 0), tests)}   "
                 f"{st('compile/runtime fatal', st.dim)} {_pct(k.get('fatal', 0), tests)}")
        for bid, (size, t0, asan) in sorted(running.items())[:4]:
            L.append(st(f"  ▸ batch {bid:<6} {size:>4} tests  {time.time() - t0:5.1f}s  "
                        f"{'ASan heap' if asan else 'Zend MM'}", st.dim))
        L.append("")

        # -- per strategy ---------------------------------------------------
        head = f"{'strategy':<26}{'tests':>10}{'parse-ok':>10}{'clean':>9}{'fatal':>9}{'timeout':>9}{'hits':>7}{'new':>6}"
        L.append(st(head, st.bold))
        for name, s in sorted(snap["by_strategy"].items(), key=lambda kv: -kv[1].get("tests", 0)):
            n = s.get("tests", 0)
            new_b = s.get("new_bugs", 0)
            L.append(f"{name:<26}{n:>10,}{_pct(n - s.get('parse', 0), n):>10}{_pct(s.get('ok', 0), n):>9}"
                     f"{_pct(s.get('fatal', 0), n):>9}{_pct(s.get('timeout', 0), n):>9}"
                     f"{s.get('bug', 0):>7}" + (st(f"{new_b:>6}", st.red, st.bold) if new_b else f"{0:>6}"))
        L.append("")
        ex = "  ".join(f"{name} {s.get('tests', 0):,}" + (st(f" ({s.get('bug', 0)} hits)", st.red) if s.get('bug') else "")
                       for name, s in sorted(snap["by_exec"].items()))
        L.append(st("exec modes ", st.dim) + (ex or "-"))
        L.append(rule)

        # -- recent bugs + log ---------------------------------------------
        remaining = max(4, rows - len(L) - 2)
        bug_rows = min(len(snap["recent_bugs"]), max(2, remaining // 2))
        L.append(st("recent bugs", st.bold) + st(f"   ({info.get('bugs_dir', 'bugs')}/index.json)", st.dim))
        if not snap["recent_bugs"]:
            L.append(st("  none yet", st.dim))
        for t, is_new, sig, strat, bdir in list(snap["recent_bugs"])[-bug_rows:][::-1]:
            tag = st(" NEW ", st.bold, "41") if is_new else st(" dup ", st.dim)
            where = os.path.relpath(bdir, info.get("root", ".")) if bdir else "-"
            L.append(f"  {time.strftime('%H:%M:%S', time.localtime(t))} {tag} {st(sig, st.red if is_new else '0')}"
                     f"  {st(strat, st.magenta)}  {st(where, st.dim)}")
        L.append(st("log", st.bold))
        log_rows = max(1, rows - len(L) - 1)
        for _seq, t, msg in snap["events"][-log_rows:]:
            L.append(st(f"  {time.strftime('%H:%M:%S', time.localtime(t))} ", st.dim) + msg)
        return L


class PlainReporter:
    def __init__(self, stats, interval: float = 30.0, stream=None):
        self.stats = stats
        self.interval = interval
        self.stream = stream or sys.stdout
        self._stop = threading.Event()
        self._thread = None
        self._seen_events = 0

    def start(self):
        self._thread = threading.Thread(target=self._loop, daemon=True, name="ui")
        self._thread.start()

    def stop(self):
        self._stop.set()
        if self._thread:
            self._thread.join(timeout=5)
        self._flush(force_status=False)

    def _flush(self, force_status: bool):
        snap = self.stats.snapshot()
        for seq, t, msg in snap["events"]:
            if seq > self._seen_events:
                print(f"{time.strftime('%H:%M:%S', time.localtime(t))} {msg}", file=self.stream)
                self._seen_events = seq
        if force_status:
            print(_summary_line(snap), file=self.stream)
        self.stream.flush()

    def _loop(self):
        last = 0.0
        while not self._stop.is_set():
            now = time.time()
            due = now - last >= self.interval
            self._flush(force_status=due)
            if due:
                last = now
            self._stop.wait(1.0)


def final_report(snap, unique_bugs: int, bugs_dir: str) -> str:
    k = snap["kinds"]
    t = snap["tests"] or 1
    lines = [
        "FlowFusion finished after " + _fmt_dur(snap["elapsed"]),
        f"  tests executed : {snap['tests']:,} ({snap['rate']:.1f}/s)",
        f"  parse-ok       : {100.0 * (t - k.get('parse', 0)) / t:.1f}%",
        f"  bug hits       : {snap['bugs']:,}   new signatures: {snap['new_signatures']}   unique total: {unique_bugs}",
        f"  timeouts/lost  : {k.get('timeout', 0):,} / {k.get('lost', 0):,}",
    ]
    for name, s in sorted(snap["by_strategy"].items(), key=lambda kv: -kv[1].get("tests", 0)):
        n = s.get("tests", 0)
        lines.append(f"  {name:<24} tests {n:>9,}  parse-ok {_pct(n - s.get('parse', 0), n)}  "
                     f"hits {s.get('bug', 0):>5}  new {s.get('new_bugs', 0)}")
    lines.append(f"  bugs: {bugs_dir}/index.json")
    return "\n".join(lines)
