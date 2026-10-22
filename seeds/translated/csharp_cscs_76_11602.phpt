--TEST--
Translated seed (inferredbugs-csharp: cscs/76)
--FILE--
<?php
$a = [[1], [2, 3]];
$b = $a;
$b[0][0] = 9;
$a[1][] = 4;

$s = "123";
$n = (int)$s;
$f = (float)$s;
$bool = (bool)$s;

$x = [];
$x["k"] = [];
$x["k"]["v"] = 7;

$y = ["a" => 1, "b" => 2];
unset($y["a"]);
$y["c"] = 3;

$z = array_merge($a, [$x, $y]);
$z[] = ["end" => true];

echo "";
?>
--EXPECT--
