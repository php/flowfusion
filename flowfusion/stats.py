"""Thread-safe fuzzing statistics, shared by the runners and the UI."""

import collections
import json
import os
import threading
import time

KINDS = ("ok", "fatal", "parse", "timeout", "bug", "skip", "lost")


class Stats:
    def __init__(self):
        self._lock = threading.Lock()
        self.start = time.time()
        self.tests = 0
        self.batches = 0
        self.kinds = collections.Counter()
        self.by_strategy = collections.defaultdict(collections.Counter)
        self.by_exec = collections.defaultdict(collections.Counter)
        self.bugs = 0                     # bug-classified tests
        self.new_signatures = 0
        self.recent_bugs = collections.deque(maxlen=50)
        self.events = collections.deque(maxlen=200)   # (seq, time, message)
        self._event_seq = 0
        self._rate = collections.deque()  # (time, cumulative tests)
        self.running = {}                 # batch id -> (size, start time, asan)
        self.info = {}                    # static run information for the UI

    def event(self, msg: str):
        with self._lock:
            self._event_seq += 1
            self.events.append((self._event_seq, time.time(), msg))

    def batch_started(self, bid, size, asan):
        with self._lock:
            self.running[bid] = (size, time.time(), asan)

    def batch_done(self, bid, outcomes):
        """outcomes: list of (test, Outcome)."""
        now = time.time()
        with self._lock:
            self.running.pop(bid, None)
            self.batches += 1
            for test, oc in outcomes:
                self.tests += 1
                self.kinds[oc.kind] += 1
                s = self.by_strategy[test.strategy]
                s["tests"] += 1
                s[oc.kind] += 1
                e = self.by_exec[test.exec_mode.split(':')[0]]
                e["tests"] += 1
                e[oc.kind] += 1
                if oc.kind == "bug":
                    self.bugs += 1
            self._rate.append((now, self.tests))
            while self._rate and now - self._rate[0][0] > 120:
                self._rate.popleft()

    def bug_found(self, is_new, signature, strategy, bug_dir):
        with self._lock:
            if is_new:
                self.new_signatures += 1
                self.by_strategy[strategy]["new_bugs"] += 1
            self.recent_bugs.append((time.time(), is_new, signature, strategy, bug_dir))

    def snapshot(self):
        with self._lock:
            now = time.time()
            elapsed = max(now - self.start, 1e-6)
            recent = 0.0
            if len(self._rate) >= 2:
                (t0, c0), (t1, c1) = self._rate[0], self._rate[-1]
                if t1 > t0:
                    recent = (c1 - c0) / (t1 - t0)
            return {
                "elapsed": elapsed, "tests": self.tests, "batches": self.batches,
                "rate": self.tests / elapsed, "recent_rate": recent or self.tests / elapsed,
                "kinds": dict(self.kinds), "bugs": self.bugs, "new_signatures": self.new_signatures,
                "by_strategy": {k: dict(v) for k, v in self.by_strategy.items()},
                "by_exec": {k: dict(v) for k, v in self.by_exec.items()},
                "recent_bugs": list(self.recent_bugs), "events": list(self.events),
                "running": dict(self.running), "info": dict(self.info),
            }

    def dump(self, path: str, extra: dict = None):
        snap = self.snapshot()
        snap.pop("events", None)
        snap["recent_bugs"] = [
            {"time": time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(t)), "new": n,
             "signature": s, "strategy": st, "dir": d} for t, n, s, st, d in snap["recent_bugs"]]
        snap["running"] = len(snap["running"])
        snap.update(extra or {})
        tmp = path + ".tmp"
        with open(tmp, "w") as f:
            json.dump(snap, f, indent=2)
        os.replace(tmp, path)
