--TEST--
Translated seed (inferredbugs-csharp: OpenCoreMMO/5)
--FILE--
<?php
$downItems = [
    ['id' => 5],
    ['id' => 6],
    ['id' => 6],
    ['id' => 7, 'amount' => 100],
    ['id' => 7, 'amount' => 15],
    ['id' => 8, 'amount' => 3],
];

$topItems = [
    ['id' => 2, 'kind' => 'top', 'amount' => 1],
];

$tile = ['top' => $topItems, 'down' => array_reverse($downItems)];

$item = ['id' => 500, 'amount' => 100];
$tile['down'][] = $item;
$removed = array_pop($tile['down']);

$c1 = ['id' => 500, 'amount' => 5];
$c2 = ['id' => 500, 'amount' => 3];
$stack = [$c1, ['id' => 1, 'kind' => 'top', 'amount' => 1], ['id' => 200]];
$stack[0]['amount'] += $c2['amount'];

$overflow = ['id' => 500, 'amount' => 60];
$stack2 = [$overflow, ['id' => 1, 'kind' => 'top', 'amount' => 1]];
$stack2[] = ['id' => 500, 'amount' => 100];

$src = [];
$dst = [];
$move = ['id' => 100];
$src[] = $move;
$dst[] = array_pop($src);

$ammo = ['id' => 100, 'amount' => 100];
$src2 = [$ammo];
$dst2 = [['id' => 100, 'amount' => 50]];
$src2[0]['amount'] -= 80;
$dst2[0]['amount'] += 80;
$src2[0]['amount'] -= 50;
$dst2[0]['amount'] += 50;
if ($src2[0]['amount'] === 0) array_pop($src2);

$tiles = [
    [101, 100, 7], [101, 101, 7], [100, 101, 7], [99, 100, 7],
    [100, 99, 7], [99, 99, 7], [101, 99, 7], [99, 101, 7]
];
$origin = [100, 100, 7];
foreach ($tiles as $t) {
    $next = abs($origin[0] - $t[0]) <= 1 && abs($origin[1] - $t[1]) <= 1 && $origin[2] === $t[2];
}

$blocked = [100, 100, 8];
$hole = true;
$itemFalls = $hole && !($blocked[0] === 100 && $blocked[1] === 100 && $blocked[2] === 8);

echo "";
?>
--EXPECT--
