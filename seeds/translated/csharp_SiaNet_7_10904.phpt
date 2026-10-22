--TEST--
Translated seed (inferredbugs-csharp: SiaNet/7)
--FILE--
<?php
$filters = 3;
$kernelSize = 2;
$strides = 1;
$padding = 1;
$useBias = true;

$x = [
    [[1, 2, 3, 4, 5]],
    [[6, 7, 8, 9, 10]]
];

$n = count($x);
$c = count($x[0]);
$s = count($x[0][0]);

$stepsOut = intdiv($s - $kernelSize + 2 * $padding, $strides) + 1;

$xCols = [];
for ($i = 0; $i < $n; $i++) {
    for ($step = 0; $step < $stepsOut; $step++) {
        for ($ch = 0; $ch < $c; $ch++) {
            for ($k = 0; $k < $kernelSize; $k++) {
                $idx = $step * $strides + $k - $padding;
                $xCols[] = ($idx >= 0 && $idx < $s) ? $x[$i][$ch][$idx] : 0;
            }
        }
    }
}

$w = [];
for ($f = 0; $f < $filters; $f++) {
    for ($j = 0; $j < $c * $kernelSize; $j++) {
        $w[$f][$j] = ($f + 1) * ($j + 1);
    }
}

$out = [];
for ($f = 0; $f < $filters; $f++) {
    for ($col = 0; $col < count($xCols) / ($c * $kernelSize); $col++) {
        $sum = 0;
        for ($j = 0; $j < $c * $kernelSize; $j++) {
            $sum += $w[$f][$j] * $xCols[$col * $c * $kernelSize + $j];
        }
        if ($useBias) {
            $sum += $f + 1;
        }
        $out[$f][$col] = $sum;
    }
}

$y = [];
for ($i = 0; $i < $n; $i++) {
    for ($f = 0; $f < $filters; $f++) {
        for ($step = 0; $step < $stepsOut; $step++) {
            $y[$i][$f][$step] = $out[$f][$i * $stepsOut + $step];
        }
    }
}

echo count($y) . "\n";
?>
--EXPECT--
