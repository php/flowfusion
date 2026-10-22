--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/9)
--FILE--
<?php
$items = [0, 1, 2, 3, 4];
$idx = -1;
foreach ($items as $i => $v) {
    if ($v === 3) { $idx = $i; break; }
}

$filtered = array_values(array_map(fn($x) => $x * 2, array_filter($items, fn($x) => $x % 2 === 0)));

$pageSize = 2;
$count = count($items);
$page = intdiv($count + $pageSize - 1, $pageSize);

$nested = [[['a' => ['b' => 1]]]];
$val = $nested[0][0]['a']['b'];

$enum = match ($idx) {
    3 => 1,
    default => 0,
};
$enumInt = (int)$enum;

$sum = 0;
foreach ($filtered as $v) $sum += $v;

echo $idx . "\n";
echo $page . "\n";
echo $val . "\n";
echo $enumInt . "\n";
echo $sum . "\n";
?>
--EXPECT--
