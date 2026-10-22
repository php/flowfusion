--TEST--
Translated seed (inferredbugs-csharp: SiaNet/15)
--FILE--
<?php
$counter = 0;
$a = [1.0, -2.5, 0.0, 3.75];
$b = [0.5, 2.0, -1.0, 4.0];

$r1 = [];
foreach ($a as $v) {
    $r1[] = abs($v);
}

$r2 = [];
foreach ($a as $v) {
    $r2[] = $v + $b[0];
}

$r3 = [];
foreach ($a as $v) {
    $r3[] = $v / 0.5;
}

$r4 = [];
foreach ($a as $v) {
    $r4[] = $v > $b[1];
}

$r5 = [];
foreach ($a as $v) {
    $r5[] = $v < 1.5 ? $v : 1.5;
}

$sum = array_sum($a);
$mean = $sum / count($a);
$max = max($a);
$min = min($a);

$shape = [2, 2];
$matrix = array_chunk($a, 2);
$transpose = [$matrix[0][0], $matrix[1][0], $matrix[0][1], $matrix[1][1]];

$rows = array_slice($transpose, 1, 2);
$tile = array_merge($rows, $rows);

$uuid = "name_" . $counter++;

echo $uuid . "\n";
echo json_encode([$r1, $r2, $r3, $r4, $r5, $sum, $mean, $max, $min, $shape, $transpose, $tile], JSON_PRESERVE_ZERO_FRACTION) . "\n";
?>
--EXPECT--
