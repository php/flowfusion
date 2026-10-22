--TEST--
Translated seed (inferredbugs-csharp: SiaNet/2)
--FILE--
<?php
$x = [[[1, 2, 3], [4, 5, 6], [7, 8, 9]]];
$padding = 1;
$stride = 1;

$p = [];
foreach ($x as $n => $ch) {
    $ph = [];
    $h = count($ch);
    $w = count($ch[0]);
    for ($i = 0; $i < $h + 2 * $padding; $i++) {
        $row = [];
        for ($j = 0; $j < $w + 2 * $padding; $j++) {
            $row[] = ($i >= $padding && $i < $h + $padding && $j >= $padding && $j < $w + $padding) ? $ch[$i - $padding][$j - $padding] : 0;
        }
        $ph[] = $row;
    }
    $p[] = $ph;
}

$cols = [];
foreach ($p as $n => $ch) {
    $h = count($ch);
    $w = count($ch[0]);
    for ($i = 0; $i + 1 <= $h; $i += $stride) {
        for ($j = 0; $j + 1 <= $w; $j += $stride) {
            $cols[] = $ch[$i][$j];
        }
    }
}

$shape = [count($p), count($p[0]), count($p[0][0]), count($p[0][0])];
echo count($cols) . PHP_EOL;
echo $shape[0] . ' ' . $shape[1] . ' ' . $shape[2] . ' ' . $shape[3] . PHP_EOL;
?>
--EXPECT--
