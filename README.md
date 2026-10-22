## FlowFusion — A Fusion-Based Fuzzer for the PHP Interpreter

### What is FlowFusion?

FlowFusion is a fully automated fuzzer that detects bugs (memory errors, undefined behavior, assertion failures) in the PHP interpreter. It builds new test programs by **fusing** the official `.phpt` tests maintained by PHP developers — plus thousands of programs translated into PHP from other languages' bug corpora — and runs them through PHP's own test harness on an ASan/UBSan/assertion-enabled build.

### How does FlowFusion work?

With ~25K seed programs, pairwise combinations already exceed 600M fused programs; fusing three grows that exponentially, and every fusion step is itself randomized. FlowFusion has three fusion strategies:

- **Dataflow fusion** connects a value of seed A to a use in seed B. About 96% of `.phpt` files have sequential control flow, so their dataflow captures most of their semantics. The connection is either a `$fusion = $a_var;` bridge that replaces a variable of B, a rename of B's variable to A's (so both programs alias the same value), or — for programs without variables — a bridge into one of B's literals.

  ```php
  /* seed A */
  $dom = new DOMDocument;
  $dom->loadXML(..);
  $nodes = $dom->documentElement->firstChild->childNodes;
  $fusion = $nodes;                       // dataflow fusion
  /* seed B */
  foreach ($fusion as $str) { $enc = base64_encode($str); }
  // AddressSanitizer: heap-use-after-free
  ```

- **State fusion (new feature)** cuts both seeds at an intermediate program point — sampled in proportion to how many variables are live there — and interleaves the four pieces `A_prefix + B_prefix + A_suffix + B_suffix`, so each piece runs on top of state the other program produced. Cuts are only made between complete top-level statements (never inside a heredoc, an `if`/`else` chain, an alternative-syntax block or a multi-line expression).

- **Declaration fusion (new feature)** makes a declaration of one seed depend on a declaration of the other: `class Host implements DonorInterface`, `use DonorTrait;`, `class Host extends DonorClass`, or instantiating a donor class from the host. Such fusions reach class-linking and inheritance-checking code that value-level fusion never touches.

With `--chain-rate` probability the fused program is fused again with a third seed using another strategy. On top of fusion, FlowFusion mutates seeds lightly, calls random internal functions and class methods with the program's own variables (and typed boundary values), and fuzzes the runtime configuration — notably the JIT (`tracing`, `function` and several CRTO modes).

### Instructions

The steps below fuzz the latest `php-src` inside a Docker container.

1. **Start Docker**
   ```bash
   docker run --name phpfuzz -dit 0599jiangyc/flowfusion:latest bash
   docker exec -it phpfuzz bash          # user/password: phpfuzz/phpfuzz
   ```
   (or build `docker/Dockerfile` yourself.)

2. **Clone FlowFusion & prepare**
   ```bash
   git clone https://github.com/php/flowfusion.git
   cd flowfusion
   ./prepare.sh
   ```
   This clones and builds php-src (ASan + UBSan + assertions), collects the seeds into `knowledges/seeds.db` with their fixtures in `phpt_deps/`, collects function/class knowledge, and drops seeds the PHP compiler rejects. php-src itself is not modified. It takes a while; `./prepare.sh --php-src /path/to/php-src --no-build` reuses an existing build.

3. **Start fuzzing** (inside `tmux` to keep it running)
   ```bash
   python3 main.py
   ```

   ```
     FlowFusion   PHP interpreter fuzzer                                                            00:09:59
   ────────────────────────────────────────────────────────────────────────────────────────────────────────────────
   target   PHP 8.7.0-dev (cli) (ZTS DEBUG)  …/php-src/sapi/cli/php
   seeds    24,039  (187 langspec, 20,622 php-src, 3,230 translated)   apis 1,559  classes 210
   harness  run-tests.php via /usr/bin/php (non-sanitized) · 4 runners × -j3 · batch 72 · timeout 10s · Zend MM bat
   fusion   dataflow, state, declaration · chain 15% · mutation on · apifuzz 50% · classfuzz 30% · jit 45%
   ────────────────────────────────────────────────────────────────────────────────────────────────────────────────
   tests 10,512   speed   19.8/s (avg 17.5)   batches 146  running 4
   parse-ok █████████████████████████████░  98.2%   runs clean  91.1%   compile/runtime fatal   6.1%
     ▸ batch 147      72 tests   10.0s  Zend MM
     ▸ batch 148      72 tests   10.0s  Zend MM
     ▸ batch 149      72 tests    9.3s  Zend MM
   strategy                       tests  parse-ok    clean    fatal  timeout   hits   new
   state                          3,869     98.1%    93.7%     3.6%     0.7%      2     1
   dataflow                       3,730     98.4%    92.1%     5.3%     0.9%      3     3
   declaration                    1,308     98.5%    84.7%    12.8%     0.9%      2     0
   state>state                      314     95.2%    90.1%     4.1%     0.6%      1     1
   state>dataflow                   279     97.1%    91.8%     4.7%     0.7%      0     0
   …
   
   exec modes interp 4,154 (5 hits)  jit 4,776 (3 hits)  opcache 1,582 (1 hits)
   ────────────────────────────────────────────────────────────────────────────────────────────────────────────────
   recent bugs / log  (new crash signatures as they are found)
   ```

   A live dashboard shows throughput, the parse-ok/clean/fatal/timeout rates of every strategy and execution mode, running batches, newly found bugs and a log. Without a terminal (`nohup`, CI) it falls back to plain log lines (`--ui plain`). `Ctrl-C` stops cleanly and prints a summary; `logs/stats.json` is refreshed every 10 seconds.

4. **Look at the bugs**
   ```bash
   cat bugs/index.json          # one entry per unique crash signature
   ls bugs/7/                   # test.phpt test.php test.out test.sh meta.json + fixtures
   bugs/7/test.sh               # reproduce
   python3 bot.py               # reduce and write bug reports to bug_reports/
   ```
   Each crash is identified by its first relevant php-src stack frame (e.g. `asan:heap-use-after-free in zend_hash_index_find (zend_hash.c)`), so the same bug hit by many fused tests is one entry with a few reproducers (`--max-dups`).

### Useful options

```
python3 main.py --strategies state,declaration     # only these strategies
python3 main.py --weights dataflow=2,state=1       # relative strategy weights
python3 main.py -j 32 --runners 4 --timeout 10     # parallelism and per-test timeout
python3 main.py --time-limit 24h                   # or --max-tests N
python3 main.py --work-dir /dev/shm/ff             # stage batches on a tmpfs
python3 main.py --generate 20 --out /tmp/fused     # only write fused tests, don't run
python3 main.py --help                             # everything else
```

### Layout

| path | role |
|------|------|
| `main.py` | command line; fuzzing (default) or `--generate` |
| `prepare.py` / `prepare.sh` | build PHP, collect seeds, fixtures and knowledge |
| `flowfusion/fusion.py` | dataflow, state and declaration fusion; chaining |
| `flowfusion/state.py` | live-variable state points and legal cut positions |
| `flowfusion/phplex.py` | PHP lexical helpers (masking, statement splitting, wrapping) |
| `flowfusion/generator.py` | fused program → `.phpt`; per-test fixture staging |
| `flowfusion/harness.py` | batch execution with php-src's `run-tests.php` |
| `flowfusion/triage.py` | outcome classification, crash signatures, bug store |
| `flowfusion/fuzzer.py` | producer/runner pipeline |
| `flowfusion/ui.py`, `stats.py` | dashboard and statistics |
| `flowfusion/knowledge.py`, `mutator.py`, `corpus.py` | API/class/INI fuzzing, mutation, seeds |
| `seeds/translated/` | 3,230 programs translated into PHP from Java/C# bug corpora |

### Running tests with the official harness

Every batch is executed by php-src's own `run-tests.php` (with `--asan`, so ASan sees every allocation instead of Zend MM chunks; `--zendmm-ratio` of batches keep the Zend allocator for heap-corruption bugs that only show inside it). FlowFusion runs the harness itself with a plain, non-sanitized PHP when one is on `PATH` (the Docker image has one): the sanitized debug binary is slow as a harness and its `stream_select()` gets interrupted, which stops per-test timeouts from firing. Each test has its own directory with links to its parents' fixtures; each batch runs in its own process session and is killed as a whole when it ends, so no stray `php` process survives. Several harness instances run at once (`--runners`), so one batch's slow tail overlaps with the next batch.

### Development

```bash
python3 -m unittest discover -s tests     # unit tests, no PHP build needed
```

### Bugs

FlowFusion has already discovered [hundreds of bugs](https://github.com/php/php-src/issues?q=author%3AYuanchengJiang%20) in the PHP interpreter.

### Research Paper

[Fuzzing the PHP Interpreter via Dataflow Fusion](https://yuanchengjiang.github.io/docs/flowfusion.pdf).

Check [FusionFuzz](https://github.com/fusion-fuzz/fusion-fuzz) for a more general fusion-based fuzzing tool for various language processors.
