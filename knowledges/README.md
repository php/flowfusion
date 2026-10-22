FlowFusion uses pre-collected knowledge about PHP functions, classes and seed programs so it does not need to parse them while fuzzing. `prepare.py` builds all of it:

- `apis.db`: functions of the target build and their parameter types (`function.php` → `apis.json` → `function.py`)
- `class.db`: instantiable classes, their properties and public methods (`class.php` → `class.json` → `class.py`)
- `seeds.db`: every seed program (php-src tests, php-langspec tests, `seeds/translated/`) with its INI and EXTENSIONS sections, the php-src directory holding its fixtures, its dataflow groups, and whether `php -l` accepts it (`valid`)

To rebuild only the seed database: `python3 prepare.py --seeds-only`.
