--TEST--
Translated seed (inferredbugs-csharp: python-language-server/13)
--FILE--
<?php
$a = [1, 2];
$b = [3, 4];
$c = $a + $b;
$d = $a . $b;
$e = $a == $b;
$f = $a === $b;
$g = $a <=> $b;
$h = !$e;
$i = ~$a[0];
$j = -$a[1];
$k = +$a[0];
$m = $a[0] + $b[1];
$n = $a[0] * $b[0];
$o = $a[0] / 2;
$p = $a[0] % 2;
$q = $a[0] << 1;
$r = $a[0] >> 1;
$s = $a[0] & $b[0];
$t = $a[0] | $b[0];
$u = $a[0] ^ $b[0];
$v = $a[0] ** 2;
$w = $a[0] . "x";
$x = "y" . $a[0];
$y = "z" % $a[0];
$z = (bool)$a;
echo "";
?>
--EXPECT--
