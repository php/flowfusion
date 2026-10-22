--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/2)
--FILE--
<?php
$s = [1, 2, 3, 4];
$p = 1;
$r = array_slice($s, $p);
$r[0] = 99;
$z = [];
$z[] = count($s);
$z[] = count($r);
$z[] = $s[1];
$z[] = $r[0];
$shape = [];
if (count($shape) === 0) {
    $shape = [0];
}
$len = 1;
foreach ($shape as $d) {
    $len *= $d;
}
if ($len !== 0) {
    $t = array_fill(0, $len, 0);
} else {
    $t = [];
}
$flat = [1, 2, 3, 4];
$rows = 2;
$cols = 2;
if ($rows * $cols !== count($flat)) {
    exit(0);
}
$m = array_chunk($flat, $cols);
$n = $m;
$n[0][0] = 7;
echo "";
?>
--EXPECT--
