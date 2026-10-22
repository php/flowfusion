--TEST--
Translated seed (inferredbugs-csharp: NLua/2)
--FILE--
<?php
$s = "a(b(c)d)e";
if (preg_match('/\((?:[^()]|(?R))*\)/', $s, $m, PREG_OFFSET_CAPTURE)) {
    $x = substr($s, $m[0][1] + 1, strlen($m[0][0]) - 2);
}
$t = "abc123xyz";
preg_match_all('/(\d+)?([a-z]+)/', $t, $m, PREG_SET_ORDER);
$r = '';
foreach ($m as $v) $r .= $v[2] . ($v[1] ?? '');
$u = str_repeat("ab", 3);
$v = strrev($u);
$w = strtolower("AbC");
$z = strtoupper("aBc");
$n = strlen($s);
$a = substr($s, -5, 3);
$b = strpos($t, "123");
$c = preg_replace('/(\d+)/', '[$1]', $t, 1);
$d = sprintf("%04d-%s-%q", 7, "hi", "x");
echo $x, "\n", $r, "\n", $u, "\n", $v, "\n", $w, "\n", $z, "\n", $n, "\n", $a, "\n", $b, "\n", $c, "\n", $d, "\n";
?>
--EXPECT--
