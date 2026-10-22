--TEST--
Translated seed (inferredbugs-csharp: SiaNet/3)
--FILE--
<?php
$x = [[[1, 2, 3], [4, 5, 6], [7, 8, 9]]];
$padding = 1;
$stride = 1;

$h = count($x);
$w = count($x[0]);
$p = array_fill(0, $w + 2 * $padding, 0);
$y = array_fill(0, $h + 2 * $padding, $p);
for ($i = 0; $i < $h; $i++) {
    for ($j = 0; $j < $w; $j++) {
        $y[$i + $padding][$j + $padding] = $x[$i][$j];
    }
}

$cols = [];
for ($i = 0; $i <= count($y) - 3; $i += $stride) {
    for ($j = 0; $j <= count($y[0]) - 3; $j += $stride) {
        $cols[] = [
            $y[$i][$j], $y[$i][$j + 1], $y[$i][$j + 2],
            $y[$i + 1][$j], $y[$i + 1][$j + 1], $y[$i + 1][$j + 2],
            $y[$i + 2][$j], $y[$i + 2][$j + 1], $y[$i + 2][$j + 2],
        ];
    }
}

echo json_encode($cols), PHP_EOL;
--EXPECT--
