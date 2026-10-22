--TEST--
Translated seed (inferredbugs-csharp: SiaNet/14)
--FILE--
<?php
$x = [[1.0, 2.0], [3.0, 4.0]];
$dim = 3;
$useBias = true;

$w = [];
for ($i = 0; $i < count($x[0]); $i++) {
    $row = [];
    for ($j = 0; $j < $dim; $j++) {
        $row[] = 0.1 * ($i + $j + 1);
    }
    $w[] = $row;
}

$out = [];
for ($i = 0; $i < count($x); $i++) {
    $row = [];
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($k = 0; $k < count($x[0]); $k++) {
            $s += $x[$i][$k] * $w[$k][$j];
        }
        if ($useBias) {
            $s += 0.0;
        }
        $row[] = max(0.0, $s);
    }
    $out[] = $row;
}

$gradOut = [[1.0, -1.0, 2.0], [0.5, 0.0, -0.5]];
$gradAct = [];
for ($i = 0; $i < count($out); $i++) {
    $row = [];
    for ($j = 0; $j < $dim; $j++) {
        $row[] = $out[$i][$j] > 0.0 ? $gradOut[$i][$j] : 0.0;
    }
    $gradAct[] = $row;
}

$inputGrad = [];
for ($i = 0; $i < count($x); $i++) {
    $row = [];
    for ($k = 0; $k < count($x[0]); $k++) {
        $s = 0.0;
        for ($j = 0; $j < $dim; $j++) {
            $s += $gradAct[$i][$j] * $w[$k][$j];
        }
        $row[] = $s;
    }
    $inputGrad[] = $row;
}

$wGrad = [];
for ($k = 0; $k < count($x[0]); $k++) {
    $row = [];
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($i = 0; $i < count($x); $i++) {
            $s += $x[$i][$k] * $gradAct[$i][$j];
        }
        $row[] = $s;
    }
    $wGrad[] = $row;
}

if ($useBias) {
    $bGrad = [];
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($i = 0; $i < count($gradAct); $i++) {
            $s += $gradAct[$i][$j];
        }
        $bGrad[] = $s;
    }
}

echo "";
?>
--EXPECT--
