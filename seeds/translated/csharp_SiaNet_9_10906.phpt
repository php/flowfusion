--TEST--
Translated seed (inferredbugs-csharp: SiaNet/9)
--FILE--
<?php
$a = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
$b = [[9, 8, 7], [6, 5, 4], [3, 2, 1]];

$kernel = [[1, 1, 1], [1, 1, 1], [1, 1, 1]];
$out = [];
for ($i = 0; $i < 1; $i++) {
    for ($j = 0; $j < 1; $j++) {
        $sum = 0;
        for ($ki = 0; $ki < 3; $ki++) {
            for ($kj = 0; $kj < 3; $kj++) {
                $sum += $a[$i + $ki][$j + $kj] * $kernel[$ki][$kj];
            }
        }
        $out[$i][$j] = $sum;
    }
}

$max = [];
for ($i = 0; $i < 3; $i++) {
    $row = $a[$i];
    $max[$i] = max($row);
}

$softmax = [];
$flat = [1, -2, 3, -4, 1, 2, -3, 4];
for ($r = 0; $r < 2; $r++) {
    $slice = array_slice($flat, $r * 4, 4);
    $m = max($slice);
    $exps = [];
    $s = 0.0;
    foreach ($slice as $v) {
        $e = exp($v - $m);
        $exps[] = $e;
        $s += $e;
    }
    $softmax[$r] = array_map(fn($e) => $e / $s, $exps);
}

print_r($out);
print_r($max);
print_r($softmax);
?>
--EXPECT--
