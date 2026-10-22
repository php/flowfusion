--TEST--
Translated seed (inferredbugs-csharp: SiaNet/11)
--FILE--
<?php
$x = [[1.0, -2.0, 3.0], [4.0, 0.5, -1.5]];
$dim = 4;

$w = [];
for ($i = 0; $i < 3; $i++) {
    for ($j = 0; $j < $dim; $j++) {
        $w[$i][$j] = (($i + 1) * ($j + 2) % 5 - 2) * 0.25;
    }
}

$b = [];
for ($j = 0; $j < $dim; $j++) {
    $b[0][$j] = ($j - 1) * 0.1;
}

$out = [];
for ($i = 0; $i < count($x); $i++) {
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($k = 0; $k < count($x[$i]); $k++) {
            $s += $x[$i][$k] * $w[$k][$j];
        }
        $out[$i][$j] = $s + $b[0][$j];
    }
}

$grad = [];
for ($i = 0; $i < count($out); $i++) {
    for ($j = 0; $j < $dim; $j++) {
        $grad[$i][$j] = $out[$i][$j] >= 0 ? 1.0 : 0.0;
    }
}

$inputGrad = [];
for ($i = 0; $i < count($x); $i++) {
    for ($k = 0; $k < count($x[$i]); $k++) {
        $s = 0.0;
        for ($j = 0; $j < $dim; $j++) {
            $s += $grad[$i][$j] * $w[$k][$j];
        }
        $inputGrad[$i][$k] = $s;
    }
}

$wGrad = [];
for ($k = 0; $k < count($x[0]); $k++) {
    for ($j = 0; $j < $dim; $j++) {
        $s = 0.0;
        for ($i = 0; $i < count($x); $i++) {
            $s += $x[$i][$k] * $grad[$i][$j];
        }
        $wGrad[$k][$j] = $s;
    }
}

$bGrad = [];
for ($j = 0; $j < $dim; $j++) {
    $s = 0.0;
    for ($i = 0; $i < count($grad); $i++) {
        $s += $grad[$i][$j];
    }
    $bGrad[0][$j] = $s;
}

echo "";
?>
--EXPECT--
