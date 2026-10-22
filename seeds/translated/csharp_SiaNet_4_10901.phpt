--TEST--
Translated seed (inferredbugs-csharp: SiaNet/4)
--FILE--
<?php
$x = [[[[
    1, 2, 3, 4,
], [
    5, 6, 7, 8,
], [
    9, 10, 11, 12,
], [
    13, 14, 15, 16,
]]]];

$padding = 1;
$stride = 1;
$kernelH = 3;
$kernelW = 3;

$padded = [];
foreach ($x as $n => $cset) {
    foreach ($cset as $c => $rows) {
        $ph = array_fill(0, count($rows[0]) + 2 * $padding, 0);
        $block = array_fill(0, count($rows) + 2 * $padding, $ph);
        foreach ($rows as $i => $row) {
            foreach ($row as $j => $v) {
                $block[$i + $padding][$j + $padding] = $v;
            }
        }
        $padded[$n][$c] = $block;
    }
}

$cols = [];
foreach ($padded as $n => $cset) {
    foreach ($cset as $c => $rows) {
        $h = count($rows);
        $w = count($rows[0]);
        for ($i = 0; $i + $kernelH <= $h; $i += $stride) {
            for ($j = 0; $j + $kernelW <= $w; $j += $stride) {
                $patch = [];
                for ($ki = 0; $ki < $kernelH; $ki++) {
                    for ($kj = 0; $kj < $kernelW; $kj++) {
                        $patch[] = $rows[$i + $ki][$j + $kj];
                    }
                }
                $cols[] = $patch;
            }
        }
    }
}

$shape = [1, 1, 4, 4];
echo count($cols) . PHP_EOL;
echo $cols[0][0] . PHP_EOL;
echo $shape[0] . PHP_EOL;
?>
--EXPECT--
