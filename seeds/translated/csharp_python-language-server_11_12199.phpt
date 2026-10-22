--TEST--
Translated seed (inferredbugs-csharp: python-language-server/11)
--FILE--
<?php
$a = [1, 2];
$b = [3, 4];
$c = $a + $b;
$d = $a . $b;
$e = 1 << 3;
$f = 5 & 3;
$g = 5 | 2;
$h = 5 ^ 1;
$i = "x" . "y";
$j = "a" . 1;
$k = "n=%d" % 7;
$l = (bool)($a && $b);
$m = (bool)($a || []);
$n = !($a === $b);
$o = ~$e;
$p = -$e;
$q = +$e;
$r = $a < $b;
$s = $a == $b;
$t = $a != $b;
$u = $a instanceof ArrayObject;
$v = $a ?? $b;
$w = ($a[0] ?? 0) + ($b[1] ?? 0);
echo "";
?>
--EXPECT--
