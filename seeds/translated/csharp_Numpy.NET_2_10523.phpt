--TEST--
Translated seed (inferredbugs-csharp: Numpy.NET/2)
--FILE--
<?php
$a = array_fill(0, 6, 0);
for ($i = 0; $i < 6; $i++) $a[$i] = $i + 1;
$b = array_chunk($a, 3);
$t = [];
for ($i = 0; $i < 3; $i++) for ($j = 0; $j < 2; $j++) $t[$j][$i] = $b[$j][$i];
$flat = [];
foreach ($t as $row) foreach ($row as $v) $flat[] = $v;
$idx = [3, 3, 1, 8];
$src = [10, 9, 8, 7, 6, 5, 4, 3, 2];
$out = $src;
foreach ($idx as $k => $p) $out[$p] = $k;
$mask = [];
for ($i = 0; $i < 35; $i++) $mask[] = $i > 20;
$selected = [];
for ($i = 0; $i < 35; $i++) if ($mask[$i]) $selected[] = $i;
$norm = sqrt(4 + 1 + 1 + 4);
$ex = [];
foreach ([3, 1, -1, -3] as $v) $ex[] = $v / $norm;
echo implode(' ', $flat), "\n";
echo implode(' ', $out), "\n";
echo implode(' ', $selected), "\n";
echo implode(' ', array_map(fn($x) => round($x, 8), $ex)), "\n";
?>
--EXPECT--
