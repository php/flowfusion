--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/17)
--FILE--
<?php
function totalLength(array $sizes): int {
    $n = 1;
    foreach ($sizes as $s) $n *= $s;
    return $n;
}

function makeTensor(array $sizes): array {
    if (count($sizes) === 0) $sizes = [0];
    return ['shape' => $sizes, 'data' => array_fill(0, totalLength($sizes), 0)];
}

function reshapeTensor(array $t, array $dims): array {
    if (count($dims) === 0) return $t;
    if (totalLength($dims) !== count($t['data'])) return $t;
    return ['shape' => array_values($dims), 'data' => $t['data']];
}

function cloneEmptyTensor(array $t): array {
    return ['shape' => $t['shape'], 'data' => array_fill(0, count($t['data']), 0)];
}

$t = makeTensor([2, 3]);
$t['data'][0] = 7;
$t['data'][5] = 9;
$c = cloneEmptyTensor($t);
$r1 = reshapeTensor($t, [6]);
$r2 = reshapeTensor($t, [3, 2]);
$e = makeTensor([]);
$z = cloneEmptyTensor($e);
echo count($t['data']) . "\n";
echo count($c['data']) . "\n";
echo implode(',', $r1['shape']) . "\n";
echo implode(',', $r2['shape']) . "\n";
echo implode(',', $e['shape']) . "\n";
echo count($z['data']) . "\n";
?>
--EXPECT--
