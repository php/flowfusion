# Extra seeds

`translated/` holds 3,230 PHP programs migrated into PHP from other
languages' bug corpora (the Java and C# programs of InferredBugs) by
fusion-fuzz's seed migration. They exercise library and engine paths that
php-src's own tests rarely combine — string/array processing, JSON,
date handling, object-heavy code — and fuse with php-src tests like any
other seed.

The 35 translations that the PHP compiler rejects (`php -l`) were dropped.
Each file is a minimal `.phpt` (`--TEST--` + `--FILE--`); the file name
records the source corpus, project and bug id.

`prepare.py` loads every `.phpt` here into `knowledges/seeds.db` with
origin `translated`; `python3 main.py --translated-weight W` changes how
often they are drawn relative to php-src tests.
