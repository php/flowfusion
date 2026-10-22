--TEST--
Translated seed (inferredbugs-csharp: elastic-db-tools/2)
--FILE--
<?php
$base = ['a' => 1, 'b' => 2];
$copy = $base;
$alias =& $base;
$alias['c'] = 3;
$copy['b'] = 20;

$items = [];
$items[] = ['x' => 1, 'y' => ['z' => 2]];
$items[] = ['x' => 1, 'y' => ['z' => 2]];
$items[] = ['x' => 1, 'y' => ['z' => 3]];

$map = [];
foreach ($items as $k => $v) {
    $map[json_encode($v)] = $k;
}

$needle = ['x' => 1, 'y' => ['z' => 2]];
$key = json_encode($needle);
if (array_key_exists($key, $map)) {
    $idx = $map[$key];
    $items[$idx]['y']['z'] = 99;
}

$sum = 0;
foreach ($items as $v) {
    $sum += $v['x'] + $v['y']['z'];
}

echo $sum, "\n";
echo json_encode($base), "\n";
echo json_encode($copy), "\n";
echo json_encode($items), "\n";
?>
--EXPECT--
