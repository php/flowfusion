--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/15)
--FILE--
<?php
function totalLength(array $sizes): int {
    $n = 1;
    foreach ($sizes as $s) $n *= $s;
    return $n;
}

function createTensor(array $sizes): array {
    $shape = $sizes;
    if (count($sizes) === 0) $shape = [0];
    return ['shape' => $shape, 'data' => array_fill(0, totalLength($sizes), 0)];
}

function reshapeTensor(array $tensor, array $dimensions): array {
    if (count($dimensions) === 0) return $tensor;
    if (totalLength($dimensions) !== count($tensor['data'])) return $tensor;
    return ['shape' => $dimensions, 'data' => &$tensor['data']];
}

$t = createTensor([]);
$t['data'][0] = 1;
$r = reshapeTensor($t, [1]);
$r['data'][0] = 2;
echo $t['data'][0], "\n";
?>
--EXPECT--
