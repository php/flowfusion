--TEST--
Translated seed (inferredbugs-csharp: SiaNet/17)
--FILE--
<?php
$counter = 0;
$a = [1.0, -2.5, 0.0, 3.75];
$b = [2.0, 4.0, -1.0, 0.5];
$c = [];
foreach ($a as $i => $v) {
    $c[$i] = abs($v);
}
foreach ($c as $i => $v) {
    $c[$i] = min(max($v, 0.25), 2.5);
}
$d = [];
foreach ($c as $i => $v) {
    $d[$i] = $v + $b[$i];
}
$e = [];
foreach ($d as $i => $v) {
    $e[$i] = $v * $v;
}
$f = array_sum($e);
$g = sqrt($f);
$h = [];
foreach ($d as $i => $v) {
    $h[$i] = $v / $g;
}
$i = [];
foreach ($h as $v) {
    $i[] = $v > 0.5 ? 1 : 0;
}
$j = array_map(fn($x) => (int)round($x), $h);
$k = array_reverse($j);
$l = array_slice($k, 1, 2);
$m = array_merge($l, $l);
$n = [];
foreach ($m as $v) {
    $n[] = $v ? $counter++ : $counter++;
}
echo json_encode([$c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n], JSON_PRESERVE_ZERO_FRACTION), PHP_EOL;
?>
--EXPECT--
