--TEST--
Translated seed (inferredbugs-csharp: SiaNet/6)
--FILE--
<?php
$filters = 2;
$k1 = 3;
$k2 = 3;
$strides = 1;
$pad = 1;
$n = 1;
$c = 1;
$h = 4;
$w = 4;

$input = [];
for ($i = 0; $i < $n * $c * $h * $w; $i++) $input[] = $i + 1;

$weights = [];
for ($i = 0; $i < $filters * $c * $k1 * $k2; $i++) $weights[] = $i + 1;

$cols = [];
for ($kh = 0; $kh < $k1; $kh++) {
    for ($kw = 0; $kw < $k2; $kw++) {
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $iy = $y + $kh - $pad;
                $ix = $x + $kw - $pad;
                $cols[] = ($iy >= 0 && $iy < $h && $ix >= 0 && $ix < $w) ? $input[$iy * $w + $ix] : 0;
            }
        }
    }
}

$out = [];
$k = $c * $k1 * $k2;
for ($f = 0; $f < $filters; $f++) {
    for ($j = 0; $j < count($cols) / ($k1 * $k2 * $c); $j++) {
        $sum = 0;
        for ($i = 0; $i < $k; $i++) $sum += $weights[$f * $k + $i] * $cols[$i * (count($cols) / $k) + $j];
        $out[$f][$j] = $sum + 1;
    }
}

$hOut = intdiv($h - $k1 + 2 * $pad, $strides) + 1;
$wOut = intdiv($w - $k2 + 2 * $pad, $strides) + 1;
$final = [];
for ($n0 = 0; $n0 < $n; $n0++) {
    for ($f = 0; $f < $filters; $f++) {
        for ($y = 0; $y < $hOut; $y++) {
            for ($x = 0; $x < $wOut; $x++) {
                $final[] = $out[$f][$y * $wOut + $x];
            }
        }
    }
}
echo count($final), PHP_EOL;
?>
--EXPECT--
