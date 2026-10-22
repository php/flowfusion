--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/20)
--FILE--
<?php
$values = [0, 1, 2, 3, 4];
$grouped = [];
foreach ($values as $v) {
    $k = $v % 2;
    $grouped[$k][] = $v;
}
$out = [];
foreach ($grouped as $k => $items) {
    $out[] = [
        'key' => $k,
        'count' => count($items),
        'sum' => array_sum($items),
        'max' => max($items),
        'min' => min($items),
        'avg' => array_sum($items) / count($items),
    ];
}
$nested = [
    'a' => ['b' => ['c' => 1]],
    'x' => ['y' => ['z' => 2]],
];
$flat = [];
foreach ($nested as $k1 => $v1) {
    foreach ($v1 as $k2 => $v2) {
        foreach ($v2 as $k3 => $v3) {
            $flat[] = $k1 . '.' . $k2 . '.' . $k3 . '=' . $v3;
        }
    }
}
echo json_encode([$out, $flat], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
