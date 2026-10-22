#!/bin/bash
# Build php-src (ASan + UBSan + assertions), collect seeds and knowledge.
# Extra arguments are passed to prepare.py, e.g. --php-src /path/to/php-src
cd "$(dirname "$0")" && exec python3 prepare.py "$@"
