--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/8)
--FILE--
<?php
$values = [0, 1, 2, 3, 4];
$filtered = array_values(array_map(fn($x) => $x * 2, array_filter($values, fn($x) => ($x & 1) === 0)));
$idx = array_search(4, $filtered, true);
$pageSize = 2;
$count = count($filtered);
$page = intdiv($count + $pageSize - 1, $pageSize);
$nested = ['a' => ['b' => ['c' => 1]]];
$ref = $nested['a']['b']['c'];
$sum = 0;
for ($i = 0; $i < $page; $i++) {
    $sum += $filtered[$i] ?? 0;
}
echo $idx . "\n" . $page . "\n" . $ref . "\n" . $sum . "\n";
?>
--EXPECT--
