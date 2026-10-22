--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/11)
--FILE--
<?php
function makeTensor(array $sizes, string $type): array {
    if (!$sizes) $sizes = [0];
    $len = array_product($sizes);
    $data = array_fill(0, $len, match ($type) {
        'float' => 0.0,
        'double' => 0.0,
        'long' => 0,
        'int' => 0,
        'short' => 0,
        'byte' => 0,
    });
    return ['shape' => $sizes, 'data' => $data, 'len' => $len, 'type' => $type];
}

function reshapeTensor(array $t, array $dims): array {
    if (!$dims) throw new InvalidArgumentException('Dimensions must contain elements.');
    $newSize = array_product($dims);
    if ($newSize !== $t['len']) throw new InvalidArgumentException('Cannot reshape array due to mismatch in lengths.');
    if (count($dims) > 2) throw new InvalidArgumentException('Cannot reshape tensor with more than 4 dimensions');
    return ['shape' => $dims, 'data' => $t['data'], 'len' => $t['len'], 'type' => $t['type']];
}

foreach (['byte', 'short', 'int', 'long', 'double', 'float'] as $type) {
    $t = makeTensor([2, 3], $type);
    $u = reshapeTensor($t, [3, 2]);
    $v = ['shape' => $u['shape'], 'data' => $u['data'], 'len' => $u['len'], 'type' => $u['type']];
    $w = makeTensor([], $type);
    $x = reshapeTensor($w, [0]);
    $y = $v['data'];
    $z = $x['data'];
}
?>
--EXPECT--
