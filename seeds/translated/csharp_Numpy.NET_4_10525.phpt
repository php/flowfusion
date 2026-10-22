--TEST--
Translated seed (inferredbugs-csharp: Numpy.NET/4)
--FILE--
<?php
$a = array_fill(0, 6, null);
for ($i = 0; $i < 6; $i++) $a[$i] = random_int(-1000000, 1000000);
echo json_encode($a), PHP_EOL;

$b = range(0, 255);
$c = array_slice($b, 0);
for ($i = 0; $i < count($c); $i++) $c[$i] = $i;
$d = array_values($c);
echo json_encode($d), PHP_EOL;

$x = [[1, 2], [3, 4]];
$t = array_map(null, ...$x);
$flat = [];
foreach ($t as $row) foreach ($row as $v) $flat[] = $v;
echo json_encode($t), PHP_EOL;
echo json_encode($flat), PHP_EOL;

$s = ["apples", "foobar", "cowboy"];
$s[2] = "banana";
echo json_encode($s), PHP_EOL;

$m = [[1, 0, 0, 1], [2, 3, 0, 1]];
$keep = [2, 6, 7, 8, 9, 13];
$out = [];
foreach ($keep as $k) $out[] = [$k, isset($m[$k][0]) ? $m[$k][0] : null];
echo json_encode($out), PHP_EOL;
?>
--EXPECT--
