--TEST--
Translated seed (inferredbugs-csharp: cscs/52)
--FILE--
<?php
$lines = [["a","1"],["a","2"],["b","3"],["b","4"],["b","5"]];
$map = [];
foreach ($lines as $i => $line) {
    $k = $line[0];
    if (!isset($map[$k])) $map[$k] = [];
    $map[$k][] = $i;
}
$seq = [];
$prev = null;
$count = 0;
foreach ($lines as $row) {
    $v = $row[0];
    if ($v !== $prev) {
        $prev = $v;
        $count = 0;
    }
    $seq[] = $count++;
}
$nested = [];
$nested["x"]["y"] = 1;
$nested["x"]["z"] = 2;
$copy = $nested;
$copy["x"]["y"]++;
$copy["x"]["w"] = 3;
$items = ["10", "20", "30"];
$sum = 0;
foreach ($items as $s) $sum += (int)$s;
echo json_encode([$map, $seq, $copy, $sum], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
