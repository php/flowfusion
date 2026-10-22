--TEST--
Translated seed (inferredbugs-csharp: WeihanLi.Common/1)
--FILE--
<?php
$rows = [
    ['id' => 1, 'val' => '10', 'extra' => null],
    ['id' => 2, 'val' => '20', 'extra' => 'x'],
    ['id' => 3, 'val' => '30', 'extra' => 'y'],
];
$col = 1;
$out = [];
foreach ($rows as $r) {
    $out[] = (int)$r[array_keys($r)[$col]];
}
$scalar = $rows[0]['val'] ?? null;
$toInt = (int)$scalar;
$mapped = array_map(fn($v) => $v + $toInt, $out);
$filtered = array_values(array_filter($mapped, fn($v) => $v >= 20));
$sum = array_reduce($filtered, fn($c, $v) => $c + $v, 0);
echo $sum, PHP_EOL;
--EXPECT--
