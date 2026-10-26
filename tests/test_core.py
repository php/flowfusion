"""Unit tests for the pure-Python parts of FlowFusion (no PHP build needed).

    python3 -m unittest discover -s tests
"""

import os
import random
import tempfile
import time
import unittest

from flowfusion import phplex, state
from flowfusion.corpus import Seed, dataflow_groups, parse_phpt, without_missing_extensions
from flowfusion.fusion import DataflowFusion, DeclarationFusion, Fuser, StateFusion
from flowfusion.generator import TestGenerator
from flowfusion.harness import remove_tree
from flowfusion.config import Config
from flowfusion.knowledge import sanitize_ini
from flowfusion.mutator import Mutator
from flowfusion.triage import classify

PROGRAM = r'''<?php
declare(strict_types=1);
$a = [1, 2, 3];
$s = <<<EOT
  text with ( unbalanced { and "quotes
  EOT;
function test($x) {
    $y = $x + 1; // comment ; with }
    return $y;
}
if ($a) {
    echo "x";
} else {
    echo 'y';
}
$r = $a
    [0];
foreach ($a as $v):
   echo $v;
endforeach;
echo test(1), "\n";
?>
Done
'''


class LexTest(unittest.TestCase):
    def test_clean_and_preamble(self):
        pre, rest = phplex.extract_preamble(phplex.clean_php_code(PROGRAM))
        self.assertEqual(pre, ['declare(strict_types=1);'])
        self.assertNotIn('Done', rest)
        self.assertNotIn('?>', rest)

    def test_statements_keep_blocks_and_heredocs(self):
        stmts = phplex.split_statements(phplex.clean_php_code(PROGRAM))
        self.assertTrue(any(s.startswith('if (') and 'else' in s for s in stmts))
        self.assertTrue(any('<<<EOT' in s and s.endswith('EOT;') for s in stmts))

    def test_trailing_comment_stays_with_statement(self):
        self.assertEqual(phplex.split_statements('$a = 1;  // dataflow fusion\n$b = 2;'),
                         ['$a = 1;  // dataflow fusion', '$b = 2;'])

    def test_wrap_keeps_declarations_toplevel(self):
        out = phplex.wrap_body('$a = 1;\nfunction f() {}\nuse Foo\\Bar;\n$b = 2;')
        self.assertIn('function f() {}\nuse Foo\\Bar;', out)
        self.assertEqual(out.count('try {'), 2)

    def test_braced_namespace_gets_global_blocks(self):
        out = phplex.wrap_body('$a = 1;\nnamespace T {\n class C {}\n}\necho 2;')
        self.assertTrue(out.startswith('namespace {'))
        self.assertEqual(out.count('namespace {'), 2)

    def test_conflicting_declarations_are_renamed(self):
        out = phplex.resolve_name_conflicts("function test(){}", "function test(){} test(); $test = 1; $o->test();")
        self.assertIn('function test_ff()', out)
        self.assertIn('test_ff();', out)
        self.assertIn('$test = 1', out)
        self.assertIn('$o->test()', out)


class StateTest(unittest.TestCase):
    def test_cuts_avoid_unsafe_places(self):
        code = phplex.clean_php_code(PROGRAM)
        lines = code.splitlines()
        cuts = state.segment_cuts(code)
        for c in cuts:
            if 0 < c < len(lines):
                nxt = next((l for l in lines[c:] if l.strip()), '')
                self.assertFalse(nxt.lstrip().startswith(('else', '[0]', 'EOT', 'echo $v', 'endforeach')),
                                 f"cut before {nxt!r}")

    def test_interleave_keeps_all_lines(self):
        a = "$a = 1;\n$b = $a + 1;\necho $b;\n"
        b = "$x = 'q';\n$y = $x . 'r';\necho $y;\n"
        fused, info = state.interleave(a, b, rng=random.Random(1), tag="// state fusion")
        for line in (a + b).splitlines():
            self.assertIn(line, fused)
        self.assertGreater(info['a_cut'], 0)
        self.assertLess(info['a_cut'], 3)

    def test_live_counts_scope_functions(self):
        code = "$a = 1;\nfunction f() {\n  $z = 2;\n}\n$b = 3;"
        counts = state.live_counts(code.splitlines(), phplex.masked_text(code).splitlines())
        self.assertEqual(counts[-1], 2)       # $z does not leak out of f()


def seed(name, code, **kw):
    return Seed(name=name, code=code, dataflows=dataflow_groups(code), **kw)


class FusionTest(unittest.TestCase):
    A = seed("a", "<?php\n$arr = [1, 2];\n$n = count($arr);\nvar_dump($n);\n")
    B = seed("b", "<?php\n$str = 'abc';\n$len = strlen($str);\necho $len;\n")
    NOVARS = seed("c", "<?php\nvar_dump(str_repeat('x', 3));\n")
    CLS_A = seed("ca", "<?php\nclass Host { function m() { return 1; } }\n$h = new Host;\n", has_decl=True)
    CLS_B = seed("cb", "<?php\ninterface Donor {}\nclass Plain { function go() { return 2; } }\n", has_decl=True)

    def test_dataflow_makes_an_edge(self):
        rng = random.Random(0)
        for _ in range(20):
            prog = DataflowFusion(rng=rng).fuse(self.A, self.B)
            self.assertIn(prog.modes[0], ("bridge", "rename"))
            self.assertIn("// dataflow fusion", prog.body)

    def test_dataflow_into_literal(self):
        prog = DataflowFusion(rng=random.Random(0)).fuse(self.A, self.NOVARS)
        self.assertEqual(prog.modes, ["literal"])
        self.assertRegex(prog.body, r"str_repeat\((?:\$fusion, 3|'x', \$fusion)\)")

    def test_state_fusion(self):
        prog = StateFusion(rng=random.Random(2)).fuse(self.A, self.B)
        self.assertIn("// state fusion", prog.body)
        self.assertTrue(prog.modes[0].startswith("seg"))

    def test_declaration_fusion(self):
        d = DeclarationFusion(rng=random.Random(4))
        self.assertTrue(d.viable(self.CLS_A, self.CLS_B))
        self.assertFalse(d.viable(self.A, self.B))
        prog = d.fuse(self.CLS_A, self.CLS_B)
        self.assertIn(prog.modes[0], ("base_ref", "trait_use", "extends", "instantiate"))
        self.assertLess(prog.body.index("interface Donor"), prog.body.index("class Host"))

    def test_chaining_uses_three_parents(self):
        seeds = [self.A, self.B, self.NOVARS]
        rng = random.Random(5)
        fuser = Fuser(["dataflow", "state"], rng=rng, chain_rate=1.0)
        prog = fuser.fuse(lambda with_decl=False: rng.choice(seeds))
        self.assertEqual(len(prog.parents), 3)
        self.assertEqual(len(prog.strategies), 2)

    def test_render_phpt(self):
        cfg = Config(ini=False)
        gen = TestGenerator(cfg, None, None, None, rng=random.Random(0))
        prog = DataflowFusion(rng=random.Random(0)).fuse(
            seed("e", "<?php\n$x = 1;\n", extension="mbstring"), self.B)
        text, mode = gen.render(prog)
        sec = parse_phpt(text)
        self.assertEqual(sec["EXTENSIONS"], "mbstring")
        self.assertTrue(sec["FILE"].startswith("<?php"))
        self.assertIn("var_dump(get_defined_vars());", sec["FILE"])


class MiscTest(unittest.TestCase):
    def test_mutator_is_mostly_identity(self):
        m = Mutator(rng=random.Random(0))
        code = FusionTest.A.clean
        self.assertGreater(sum(m.mutate(code) == code for _ in range(200)), 150)

    def test_sanitize_ini(self):
        self.assertEqual(sanitize_ini("memory_limit=-1"), "memory_limit=512M")
        self.assertEqual(sanitize_ini("memory_limit=8G\nx=1"), "memory_limit=512M\nx=1")
        self.assertEqual(sanitize_ini("memory_limit=128M"), "memory_limit=128M")
        self.assertEqual(sanitize_ini("a=1\nzend_test.observe_opline_in_zendmm=1\n"
                                      "zend_test.zend_mm_custom_handlers.enabled=1\nb=2"), "a=1\nb=2")

    def test_extension_filter(self):
        s1, s2 = seed("x", "<?php", extension="intl"), seed("y", "<?php", extension="mbstring")
        kept, dropped = without_missing_extensions([s1, s2], {"mbstring"})
        self.assertEqual((kept, dropped), ([s2], 1))


class TriageTest(unittest.TestCase):
    def test_kinds(self):
        self.assertEqual(classify("Parse error: syntax error", "FAILED").kind, "parse")
        self.assertEqual(classify("Fatal error: Uncaught Exception", "FAILED").kind, "ok")
        self.assertEqual(classify("Fatal error: Cannot redeclare f()", "FAILED").kind, "fatal")
        self.assertEqual(classify("x\n ** ERROR: process timed out **", "FAILED").kind, "timeout")
        self.assertEqual(classify("", "SKIPPED").kind, "skip")

    def test_signatures(self):
        asan = ("==1==ERROR: AddressSanitizer: heap-use-after-free on address 0x1\n"
                "    #0 0x1 in zend_hash_find /src/php-src/Zend/zend_hash.c:10:2\n")
        self.assertEqual(classify(asan, "FAILED").signature, "asan:heap-use-after-free in zend_hash_find (zend_hash.c)")
        assertion = ("php: /src/Zend/zend_hash.c:1103: zval *_zend_hash_add(HashTable *, zval *): "
                     "Assertion `ht != 0' failed.\n==1==ERROR: AddressSanitizer: ABRT on unknown address\n")
        self.assertEqual(classify(assertion, "FAILED").signature, "assert:ht != N in _zend_hash_add")
        ubsan = "/src/ext/standard/math.c:12:3: runtime error: signed integer overflow: 1 + 2 cannot be represented"
        self.assertTrue(classify(ubsan, "FAILED").signature.endswith("at math.c:12"))

    def test_linear_time_on_hostile_output(self):
        for text in ("A" * 262144, "Assertion " * 20000, ("runtime error: " + "C" * 500) * 400):
            t = time.time()
            classify(text, "FAILED")
            self.assertLess(time.time() - t, 2.0)


class RemoveTreeTest(unittest.TestCase):
    def test_removes_chmodded_dirs_without_raising(self):
        # ext/standard/tests/dir/*_variation* chmod 0000 their directories;
        # cleaning up such a batch used to raise TypeError and kill a runner
        root = tempfile.mkdtemp()
        batch = os.path.join(root, "b000001")
        child = os.path.join(batch, "t0000", "dir", "sub", "child")
        os.makedirs(child)
        open(os.path.join(child, "f"), "w").close()
        for d in (child, os.path.dirname(child), batch):
            os.chmod(d, 0)
        try:
            remove_tree(batch)
            remove_tree(batch)          # already gone: no error either
            self.assertFalse(os.path.exists(batch))
        finally:
            for dirpath, dirnames, _ in os.walk(root):
                for d in dirnames:
                    os.chmod(os.path.join(dirpath, d), 0o700)
            os.chmod(root, 0o700)
            remove_tree(root)


if __name__ == "__main__":
    unittest.main()
