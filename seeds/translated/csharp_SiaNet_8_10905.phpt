--TEST--
Translated seed (inferredbugs-csharp: SiaNet/8)
--FILE--
<?php
$useBias = true;
$dim = 4;
$x = [[1.0, -2.0, 3.0], [0.5, 0.0, -1.5]];
$rows = count($x);
$cols = count($x[0]);
$w = [];
for ($i = 0; $i < $cols; $i++) {
    $w[$i] = [];
    for ($j = 0; $j < $dim; $j++) $w[$i][$j] = ($i + 1) * 0.25 + ($j - 1) * 0.1;
}
$b = array_fill(0, $dim, 0.0);
$out = [];
for ($r = 0; $r < $rows; $r++) {
    for ($j = 0; $j < $dim; $j++) {
        $s = $useBias ? $b[$j] : 0.0;
        for ($k = 0; $k < $cols; $k++) $s += $x[$r][$k] * $w[$k][$j];
        $out[$r][$j] = $s;
    }
}
$grad = [[1.0, -1.0, 2.0, -2.0], [0.5, 0.25, -0.5, -0.75]];
$inputGrad = [];
$wGrad = [];
for ($k = 0; $k < $cols; $k++) {
    for ($j = 0; $j < $dim; $j++) {
        $sum = 0.0;
        for ($r = 0; $r < $rows; $r++) $sum += $grad[$r][$j] * $w[$k][$j];
        $inputGrad[$r ?? 0][$k] = $sum;
        $wg = 0.0;
        for ($r = 0; $r < $rows; $r++) $wg += $x[$r][$k] * $grad[$r][$j];
        $wGrad[$k][$j] = $wg;
    }
}
if ($useBias) {
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($r = 0; $r < $rows; $r++) $s += $grad[$r][$j];
        $b[$j] += $s;
    }
}
echo "";
?>
--EXPECT--
