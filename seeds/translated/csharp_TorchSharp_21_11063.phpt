--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/21)
--FILE--
<?php
$t = [1, 2147483647];
$n = count($t);
$p = 1;
for ($i = 0; $i < $n; $i++) $p *= $t[$i];
if ($p > PHP_INT_MAX) {
    $p = (int)$p;
}
$s = str_repeat("x", 1);
$a = [1];
$b = array_fill(0, 1, 1);
$c = $a;
$c[0] -= $b[0];
$d = $c[0] * 1;
$e = [0];
$e[0] = $d;
echo $s . $e[0] . "\n";
?>
--EXPECT--
