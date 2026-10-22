--TEST--
Translated seed (inferredbugs-csharp: Towel/8)
--FILE--
<?php
$m = [[1, 2], [3, 4]];
$r = count($m);
$c = count($m[0]);
$flat = [];
for ($i = 0; $i < $r; $i++) {
    for ($j = 0; $j < $c; $j++) {
        $flat[] = $m[$i][$j];
    }
}
for ($i = 0; $i < $r; $i++) {
    for ($j = $i + 1; $j < $c; $j++) {
        $t = $m[$i][$j];
        $m[$i][$j] = $m[$j][$i];
        $m[$j][$i] = $t;
    }
}
$minor = [];
for ($i = 0; $i < $r; $i++) {
    if ($i === 0) continue;
    $row = [];
    for ($j = 0; $j < $c; $j++) {
        if ($j === 0) continue;
        $row[] = $m[$i][$j];
    }
    $minor[] = $row;
}
$sum = 0;
for ($i = 0; $i < count($flat); $i++) {
    $sum += $flat[$i];
}
echo $sum, "\n";
echo $minor[0][0], "\n";
?>
--EXPECT--
