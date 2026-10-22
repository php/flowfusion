--TEST--
Translated seed (inferredbugs-csharp: InlineIL.Fody/3)
--FILE--
<?php
$xs = [0, 1, 2, 3, 4];
$refs = [];
for ($i = 0; $i < 3; $i++) {
    $refs[$i] = &$xs[$i];
}
$labels = [];
$labels["a"] = null;
$labels["b"] = null;
$labels["a"] = 1;
if (!array_key_exists("c", $labels)) {
    $labels["c"] = 0;
}
$sum = 0;
foreach ($refs as &$r) {
    $sum += $r;
}
unset($r);
$opt = [];
$opt[] = $sum;
$opt[] = count($xs) > 0 ? $xs[array_key_last($xs)] : 0;
$opt[] = $labels["a"] + $labels["c"];
$rank = (int)($opt[0] % 3);
if ($rank < 1) {
    $rank = 1;
}
$arr = array_fill(0, $rank, []);
for ($i = 0; $i < $rank; $i++) {
    $arr[$i] = [$opt[$i % count($opt)]];
}
$flat = [];
foreach ($arr as $row) {
    foreach ($row as $v) {
        $flat[] = $v;
    }
}
echo implode(",", $flat), PHP_EOL;
--EXPECT--
