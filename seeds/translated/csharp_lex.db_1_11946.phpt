--TEST--
Translated seed (inferredbugs-csharp: lex.db/1)
--FILE--
<?php
$items = [
    ['id' => 0, 'name' => 'a'],
    ['id' => 1, 'name' => 'b'],
    ['id' => 0, 'name' => 'c'],
    ['id' => 2, 'name' => 'd'],
];

$max = 0;
foreach ($items as $item) {
    if ($item['id'] > $max) $max = $item['id'];
}

foreach ($items as &$item) {
    if ($item['id'] === 0) $item['id'] = $max + 1;
}
unset($item);

$keys = [];
foreach ($items as $item) $keys[] = $item['id'];

$min = null;
$max = null;
foreach ($keys as $k) {
    if ($min === null || $k < $min) $min = $k;
    if ($max === null || $k > $max) $max = $k;
}

$map = [];
foreach ($items as $item) $map[$item['id']] = $item['name'];

$deleted = 0;
foreach ([1, 3, 99, 2] as $k) {
    if (isset($map[$k])) {
        unset($map[$k]);
        $deleted++;
    }
}

$found = [];
foreach ([3, 4, 2, 1] as $k) {
    $found[] = $map[$k] ?? null;
}

echo count($items), "\n";
echo $min, " ", $max, "\n";
echo $deleted, "\n";
echo implode(",", array_map(static fn($v) => $v === null ? "null" : $v, $found)), "\n";
?>
--EXPECT--
