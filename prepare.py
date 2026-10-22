#!/usr/bin/env python3
"""
Prepare FlowFusion: build PHP, collect seeds and knowledge.

    python3 prepare.py                         # everything (clone + build if needed)
    python3 prepare.py --php-src /path/php-src --no-build
    python3 prepare.py --seeds-only            # rebuild knowledges/seeds.db only

Steps
  1. clone php-src (and php-langspec) unless present
  2. build php-src with ASan + UBSan + assertions unless sapi/cli/php exists
  3. collect seeds from php-src, php-langspec and seeds/translated into
     knowledges/seeds.db, and mirror their fixtures into phpt_deps/
     (php-src itself is never modified)
  4. collect function/class knowledge from the built binary
  5. drop seeds the PHP compiler rejects (`php -l`): every child of such a
     seed inherits the compile error
"""

import argparse
import concurrent.futures as cf
import os
import shutil
import stat
import subprocess
import sys
import tempfile
from pathlib import Path

from flowfusion import corpus
from flowfusion.config import ROOT

CONFIGURE_FLAGS = (
    "--enable-debug --enable-address-sanitizer --enable-undefined-sanitizer "
    "--enable-re2c-cgoto --enable-fpm --enable-litespeed --enable-phpdbg-debug --enable-zts "
    "--enable-bcmath --enable-calendar --enable-dba --enable-dl-test --enable-exif --enable-ftp "
    "--enable-gd --enable-gd-jis-conv --enable-mbstring --enable-pcntl --enable-shmop "
    "--enable-soap --enable-sockets --enable-sysvmsg --enable-zend-test --with-zlib --with-bz2 "
    "--with-curl --with-enchant --with-gettext --with-gmp --with-mhash --with-ldap --with-libedit "
    "--with-readline --with-snmp --with-sodium --with-xsl --with-zip --with-mysqli --with-pdo-mysql "
    "--with-pdo-pgsql --with-pgsql --with-sqlite3 --with-pdo-sqlite --with-webp --with-jpeg "
    "--with-freetype --enable-sigchild --with-pcre-jit --with-iconv"
)

ASAN_ENV = {**os.environ, "ASAN_OPTIONS": "detect_leaks=0:allocator_may_return_null=1"}


def log(msg):
    print(f"[prepare] {msg}", flush=True)


def run(cmd, **kw):
    log("$ " + (cmd if isinstance(cmd, str) else " ".join(cmd))[:160])
    subprocess.run(cmd, check=True, shell=isinstance(cmd, str), **kw)


# -- 1/2: php-src -------------------------------------------------------------

def ensure_php(php_src: Path, build: bool, jobs: int):
    php_bin = php_src / "sapi" / "cli" / "php"
    if not php_src.exists():
        run(["git", "clone", "https://github.com/php/php-src.git", str(php_src)])
    if php_bin.exists():
        log(f"using existing build {php_bin}")
        return php_bin
    if not build:
        sys.exit(f"[prepare] {php_bin} does not exist and --no-build was given")
    cc = next((c for c in ("clang-12", "clang") if shutil.which(c)), "cc")
    cxx = {"clang-12": "clang++-12", "clang": "clang++"}.get(cc, "c++")
    env = {**os.environ, "CC": cc, "CXX": cxx,
           "CFLAGS": "-DZEND_VERIFY_TYPE_INFERENCE", "CXXFLAGS": "-DZEND_VERIFY_TYPE_INFERENCE"}
    run(["./buildconf", "--force"], cwd=php_src, env=env)
    run(f"./configure {CONFIGURE_FLAGS}", cwd=php_src, env=env)
    run(["make", f"-j{jobs}", "--silent"], cwd=php_src, env=env)
    if not php_bin.exists():
        sys.exit("[prepare] build failed")
    return php_bin


def ensure_langspec(path: Path):
    if not path.exists():
        try:
            run(["git", "clone", "--depth", "1", "https://github.com/php/php-langspec.git", str(path)])
        except subprocess.CalledProcessError:
            log("could not clone php-langspec; continuing without it")


# -- 3: seeds and fixtures ----------------------------------------------------

def _flat_name(prefix: str, rel: Path) -> str:
    return prefix + "_".join(rel.with_suffix("").parts) + ".phpt"


def _make_writable(path: Path):
    for root, dirs, files in os.walk(path):
        for n in dirs + files:
            p = os.path.join(root, n)
            if not os.path.islink(p):
                os.chmod(p, os.stat(p).st_mode | stat.S_IWUSR | stat.S_IRUSR | stat.S_IXUSR * os.path.isdir(p))


def collect(sources, translated_dir: Path, deps_dir: Path):
    """Seed records and fixture mirror. `sources`: [(root, dep_prefix, origin)].

    Fixtures keep their directory layout under phpt_deps/<prefix><dir>/:
    tests reach them by their own names (`__DIR__ . '/server.inc'`), and
    hundreds of test directories reuse names such as `server.inc`."""
    if deps_dir.exists():
        _make_writable(deps_dir)
        shutil.rmtree(deps_dir)
    deps_dir.mkdir(parents=True)
    records, n_fix = [], 0
    for root, prefix, origin in sources:
        if not root.exists():
            continue
        test_dirs = set()
        for phpt in sorted(root.rglob("*.phpt")):
            if ".git" in phpt.parts:
                continue
            rel = phpt.relative_to(root)
            rel_dir = (prefix + str(rel.parent)) if str(rel.parent) != "." else prefix.rstrip("/")
            text = phpt.read_text(encoding="latin-1")
            rec = corpus.seed_record(_flat_name(prefix.replace("/", "_"), rel), text, origin, rel_dir or None)
            if rec:
                records.append(rec)
            test_dirs.add(phpt.parent)
        for d in sorted(test_dirs):
            rel = d.relative_to(root)
            target = deps_dir / (prefix + str(rel) if str(rel) != "." else prefix.rstrip("/") or ".")
            target.mkdir(parents=True, exist_ok=True)
            for item in d.iterdir():
                if item.suffix == ".phpt" or item.name == "CONFLICTS" or item.name.startswith("."):
                    continue
                dst = target / item.name
                try:
                    if item.is_file() and not dst.exists():
                        shutil.copy2(item, dst)
                        n_fix += 1
                    elif item.is_dir() and not any(item.rglob("*.phpt")) and not dst.exists():
                        shutil.copytree(item, dst)
                        n_fix += 1
                except OSError:
                    pass
    # read-only fixtures: tests open them through symlinks, and a test
    # writing through one would change the fixture for every later test
    for rootd, dirs, files in os.walk(deps_dir):
        for n in files:
            p = os.path.join(rootd, n)
            os.chmod(p, os.stat(p).st_mode & ~(stat.S_IWUSR | stat.S_IWGRP | stat.S_IWOTH))
    if translated_dir.exists():
        for phpt in sorted(translated_dir.glob("*.phpt")):
            rec = corpus.seed_record("translated_" + phpt.name, phpt.read_text(encoding="latin-1"),
                                     "translated", None)
            if rec:
                records.append(rec)
    log(f"collected {len(records):,} seeds, {n_fix:,} fixture files/dirs")
    return records


# -- 4: knowledge -------------------------------------------------------------

def collect_knowledge(php_bin: Path, kdir: Path):
    for script, builder in (("function.php", "function.py"), ("class.php", "class.py")):
        out_db = kdir / ("apis.db" if script == "function.php" else "class.db")
        if out_db.exists():
            out_db.unlink()
        try:
            subprocess.run([str(php_bin), "-n", "-d", "memory_limit=-1", script], cwd=kdir, env=ASAN_ENV,
                           check=True, timeout=600, stdout=subprocess.DEVNULL)
            subprocess.run([sys.executable, builder], cwd=kdir, check=True)
        except (subprocess.CalledProcessError, subprocess.TimeoutExpired) as e:
            log(f"knowledge step {script} failed: {e}")


# -- 5: lint ------------------------------------------------------------------

def lint_seeds(db_path: Path, php_bin: Path, jobs: int):
    import sqlite3
    conn = sqlite3.connect(str(db_path))
    rows = conn.execute("SELECT name, phpcode FROM seeds").fetchall()
    conn.close()
    log(f"linting {len(rows):,} seeds with {php_bin} ({jobs} workers)")
    rejects = []
    with tempfile.TemporaryDirectory(prefix="ff-lint") as tmp:
        def one(item):
            k, (name, code) = item
            path = os.path.join(tmp, f"l{k}.php")
            with open(path, "w", encoding="latin-1", errors="replace") as f:
                f.write(code)
            try:
                r = subprocess.run([str(php_bin), "-n", "-l", path], capture_output=True, text=True,
                                   errors="replace", timeout=60, env=ASAN_ENV)
                ok, diag = r.returncode == 0, (r.stdout + r.stderr).strip().splitlines()[:1]
            except subprocess.TimeoutExpired:
                ok, diag = False, ["lint timeout"]
            os.unlink(path)
            return name, ok, (diag[0] if diag else "")[:200]
        with cf.ThreadPoolExecutor(max_workers=jobs) as ex:
            for name, ok, diag in ex.map(one, enumerate(rows)):
                if not ok:
                    rejects.append((name, diag))
    corpus.mark_invalid(str(db_path), rejects)
    log(f"{len(rejects):,} seeds rejected by php -l (kept in the DB with valid = 0)")


def main():
    p = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    p.add_argument("--php-src", default=str(ROOT / "php-src"))
    p.add_argument("--langspec", default=str(ROOT / "php-langspec"),
                   help="php-langspec checkout (cloned if missing); '' to skip")
    p.add_argument("--no-build", action="store_true", help="fail instead of building php-src")
    p.add_argument("--seeds-only", action="store_true", help="only rebuild the seed DB and fixtures")
    p.add_argument("--no-lint", action="store_true")
    p.add_argument("-j", "--jobs", type=int, default=os.cpu_count() or 4)
    args = p.parse_args()

    php_src = Path(args.php_src).resolve()
    kdir = ROOT / "knowledges"
    if args.seeds_only:
        php_bin = php_src / "sapi" / "cli" / "php"
    else:
        php_bin = ensure_php(php_src, build=not args.no_build, jobs=args.jobs)
        if args.langspec:
            ensure_langspec(Path(args.langspec))

    sources = [(php_src, "", "php-src")]
    if args.langspec:
        sources.append((Path(args.langspec), "langspec/", "langspec"))
    records = collect(sources, ROOT / "seeds" / "translated", ROOT / "phpt_deps")
    db = kdir / "seeds.db"
    n = corpus.write_seed_db(str(db), records)
    log(f"wrote {n:,} seeds to {db}")

    if php_bin.exists():
        if not args.seeds_only:
            collect_knowledge(php_bin, kdir)
        if not args.no_lint:
            lint_seeds(db, php_bin, args.jobs)
    else:
        log(f"{php_bin} missing: skipped knowledge collection and lint")
    log("all ready! start fuzzing with: python3 main.py")


if __name__ == "__main__":
    main()
