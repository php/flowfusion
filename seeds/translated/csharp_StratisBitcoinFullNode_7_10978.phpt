--TEST--
Translated seed (inferredbugs-csharp: StratisBitcoinFullNode/7)
--FILE--
<?php
$txs = [];
for ($i = 0; $i < 3; $i++) {
    $txs["parent:$i"] = true;
    $txs["child:$i"] = true;
    $txs["grand:$i"] = true;
}
$pool = $txs;

$removeRecursive = function (string $id) use (&$pool, &$removeRecursive) {
    if (!isset($pool[$id])) return;
    unset($pool[$id]);
    if ($id === 'parent') {
        for ($i = 0; $i < 3; $i++) {
            $removeRecursive("child:$i");
            $removeRecursive("grand:$i");
        }
    } elseif (str_starts_with($id, 'child:')) {
        $i = substr($id, 6);
        $removeRecursive("grand:$i");
    }
};

$pool = [];
$removeRecursive('parent');
$pool['parent'] = true;
$removeRecursive('parent');
$pool['parent'] = true;
for ($i = 0; $i < 3; $i++) {
    $pool["child:$i"] = true;
    $pool["grand:$i"] = true;
}
$removeRecursive('child:0');
$removeRecursive('grand:0');
$removeRecursive('child:0');
$removeRecursive('parent');

$items = [
    ['id' => 'tx1', 'fee' => 10000, 'time' => 0, 'anc' => 1],
    ['id' => 'tx2', 'fee' => 20000, 'time' => 0, 'anc' => 1],
    ['id' => 'tx3', 'fee' => 0, 'time' => 0, 'anc' => 1],
    ['id' => 'tx4', 'fee' => 15000, 'time' => 0, 'anc' => 1],
    ['id' => 'tx5', 'fee' => 10000, 'time' => 1, 'anc' => 1],
    ['id' => 'tx6', 'fee' => 0, 'time' => 0, 'anc' => 1],
    ['id' => 'tx7', 'fee' => 2000000, 'time' => 0, 'anc' => 4],
    ['id' => 'tx8', 'fee' => 0, 'time' => 2, 'anc' => 2],
    ['id' => 'tx9', 'fee' => 0, 'time' => 3, 'anc' => 2],
    ['id' => 'tx10', 'fee' => 200000, 'time' => 4, 'anc' => 1],
];
usort($items, fn($a, $b) => [$a['fee'] * $a['anc'], $a['time'], $a['id']] <=> [$b['fee'] * $b['anc'], $b['time'], $b['id']]);

$orphans = [];
for ($i = 0; $i < 50; $i++) $orphans[$i] = ['deps' => 1];
for ($i = 50; $i < 100; $i++) $orphans[$i] = ['deps' => 1];
for ($i = 0; $i < 3; $i++) array_shift($orphans);
$orphans = array_slice($orphans, 0, 10);

echo "ok\n";
?>
--EXPECT--
