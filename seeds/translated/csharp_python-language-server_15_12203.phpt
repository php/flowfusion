--TEST--
Translated seed (inferredbugs-csharp: python-language-server/15)
--FILE--
<?php
$left = [];
$right = [];
$a = $left + $right;
$b = $left && $right;
$c = $left || $right;
$d = "x" . "y";
$e = 1 << 3;
$f = 1.5 / 2;
$g = 2 ** 3;
$h = (bool)0;
$i = ~1;
$j = -1;
$k = +1;
$l = "a" + 1;
$m = 1 == "1";
$n = 1 <=> 2;
$o = [1, 2] + [3, 4];
$p = ["a" => 1] + ["b" => 2];
$q = "foo" % "bar";
$r = 1 & 2;
$s = 1 | 2;
$t = 1 ^ 2;
$u = 1 << 0;
$v = 1 >> 0;
$w = 0 ? 1 : 2;
echo "";
?>
--EXPECT--
