--TEST--
Translated seed (inferredbugs-csharp: python-language-server/12)
--FILE--
<?php
$a = [1, 2];
$b = [3, 4];
$c = $a + $b;
$d = $a == $b;
$e = $a < $b;
$f = "x" . (string)($d ? 1 : 0);
$g = $e ? $c : $a;
$h = $g[0] ?? null;
$i = ~$h;
$j = -$i;
$k = +$j;
$l = $f . ($k < 0 ? "n" : "p");
echo $l;
?>
--EXPECT--
