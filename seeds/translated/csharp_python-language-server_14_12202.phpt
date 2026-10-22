--TEST--
Translated seed (inferredbugs-csharp: python-language-server/14)
--FILE--
<?php
$a = [1, 2];
$b = [3, 4];
$c = $a + $b;
$d = $a . $b;
$e = $a && $b;
$f = $a || [];
$g = ~1;
$h = -1;
$i = +1;
$j = 1 << 3;
$k = 8 >> 1;
$l = 1 & 3;
$m = 1 | 2;
$n = 1 ^ 3;
$o = 1 < 2;
$p = 1 == 1;
$q = 1 != 2;
$r = 1 <=> 2;
$s = "x" . "y";
$t = "x" . 1;
$u = "a" + "b";
$v = 1 / 2;
$w = 1 % 2;
$x = 2 ** 3;
$y = (bool)([] || [1]);
$z = (int)(true & false);
echo "";
?>
--EXPECT--
