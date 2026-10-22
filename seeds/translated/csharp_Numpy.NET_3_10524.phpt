--TEST--
Translated seed (inferredbugs-csharp: Numpy.NET/3)
--FILE--
<?php
function shape($a) { return '(' . implode(', ', array_map('strval', array_keys($a))) . ')'; }

$a = array_fill(0, 2, array_fill(0, 3, 0));
$v = 1;
for ($i = 0; $i < 2; $i++) {
    for ($j = 0; $j < 3; $j++) {
        $a[$i][$j] = $v++;
    }
}
echo json_encode($a), PHP_EOL;

$b = $a;
for ($i = 0; $i < 2; $i++) {
    for ($j = 0; $j < 3; $j++) {
        $b[$i][$j] = $a[$i][$j];
    }
}
echo json_encode($b), PHP_EOL;

$flat = [];
foreach ($a as $row) foreach ($row as $x) $flat[] = $x;
echo json_encode($flat), PHP_EOL;

$t = [];
for ($j = 0; $j < 3; $j++) {
    $row = [];
    for ($i = 0; $i < 2; $i++) $row[] = $a[$i][$j];
    $t[] = $row;
}
echo json_encode($t), PHP_EOL;

$idx = [3, 3, 1, 8];
$x = range(10, 2);
$g = [];
foreach ($idx as $i) $g[] = $x[$i];
echo json_encode($g), PHP_EOL;

$mask = [];
foreach (range(0, 34) as $n) $mask[] = $n > 20;
echo json_encode(array_values(array_filter(range(0, 34), fn($n) => $n > 20))), PHP_EOL;

$y = [];
for ($i = 0, $n = 0; $i < 5; $i++) {
    $row = [];
    for ($j = 0; $j < 7; $j++, $n++) $row[] = $n;
    $y[] = $row;
}
$out = [];
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 7; $j++) if ($y[$i][$j] > 20) $out[] = [$i, $j];
}
echo json_encode($out), PHP_EOL;
?>
--EXPECT--
