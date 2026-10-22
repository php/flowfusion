--TEST--
Translated seed (inferredbugs-csharp: SiaNet/10)
--FILE--
<?php
$dim = 3;
$useBias = true;

$x = [[1.0, -2.0], [0.5, 4.0]];
$w = [[0.1, 0.2, 0.3], [0.4, 0.5, 0.6]];
$y = [];

for ($i = 0; $i < count($x); $i++) {
    $row = [];
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($k = 0; $k < count($x[$i]); $k++) {
            $s += $x[$i][$k] * $w[$k][$j];
        }
        $row[$j] = $s;
    }
    $y[$i] = $row;
}

if ($useBias) {
    $b = [0.01, 0.02, 0.03];
    for ($i = 0; $i < count($y); $i++) {
        for ($j = 0; $j < $dim; $j++) {
            $y[$i][$j] += $b[$j];
        }
    }
}

for ($i = 0; $i < count($y); $i++) {
    for ($j = 0; $j < $dim; $j++) {
        $y[$i][$j] = $y[$i][$j] > 0 ? $y[$i][$j] : 0.0;
    }
}

$gradOut = [[1.0, -1.0, 0.5], [0.25, 0.0, -0.75]];
$wT = [[0.1, 0.4], [0.2, 0.5], [0.3, 0.6]];
$gradInput = [];

for ($i = 0; $i < count($gradOut); $i++) {
    $row = [];
    for ($j = 0; $j < count($wT); $j++) {
        $s = 0.0;
        for ($k = 0; $k < count($wT[$j]); $k++) {
            $s += $gradOut[$i][$k] * $wT[$j][$k];
        }
        $row[$j] = $s;
    }
    $gradInput[$i] = $row;
}

$xT = [[1.0, 0.5], [-2.0, 4.0]];
$gradW = [];

for ($i = 0; $i < count($xT); $i++) {
    $row = [];
    for ($j = 0; $j < count($gradOut[0]); $j++) {
        $s = 0.0;
        for ($k = 0; $k < count($xT[$i]); $k++) {
            $s += $xT[$i][$k] * $gradOut[$k][$j];
        }
        $row[$j] = $s;
    }
    $gradW[$i] = $row;
}

if ($useBias) {
    $gradB = [0.0, 0.0, 0.0];
    for ($i = 0; $i < count($gradOut); $i++) {
        for ($j = 0; $j < 3; $j++) {
            $gradB[$j] += $gradOut[$i][$j];
        }
    }
}

echo "";
?>
--EXPECT--
