--TEST--
Translated seed (inferredbugs-csharp: Numpy.NET/1)
--FILE--
<?php
$a = array_fill(0, 6, 0);
for ($i = 0; $i < 6; $i++) $a[$i] = $i + 1;
echo json_encode($a), "\n";

$b = $a;
$b[1] = 99;
echo json_encode($a), "\n";
echo json_encode($b), "\n";

$s = array_slice($a, 1, -1);
echo json_encode($s), "\n";

$idx = [3, 3, 1, 4];
$out = [];
foreach ($idx as $i) $out[] = $a[$i];
echo json_encode($out), "\n";

$mask = [];
foreach ($a as $v) $mask[] = ($v > 3);
echo json_encode($mask), "\n";

$sum = 0;
foreach ($a as $v) $sum += $v;
echo $sum, "\n";

$copy = $a;
foreach ($copy as &$v) $v *= 2;
unset($v);
echo json_encode($copy), "\n";
echo json_encode($a), "\n";

$nested = [[1, 2], [3, 4], [5, 6]];
$flat = [];
foreach ($nested as $row) foreach ($row as $v) $flat[] = $v;
echo json_encode($flat), "\n";

$transposed = [];
for ($i = 0; $i < 2; $i++) {
    $row = [];
    for ($j = 0; $j < 3; $j++) $row[] = $nested[$j][$i];
    $transposed[] = $row;
}
echo json_encode($transposed), "\n";
?>
--EXPECT--
