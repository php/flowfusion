--TEST--
Translated seed (inferredbugs-csharp: SiaNet/18)
--FILE--
<?php
$counter = 0;
$a = [1.5, -2.25, 0.0, 3.75];
$b = [2.0, 2.0, 2.0, 2.0];
$c = array_map(fn($x, $y) => $x + $y, $a, $b);
$d = array_map(fn($x) => max(min($x, 2.5), -1.0), $c);
$e = array_map(fn($x) => $x > 1.0 ? 1 : 0, $d);
$f = array_map(fn($x) => $x ? 1.0 : 0.0, $e);
$g = array_map(fn($x) => sqrt(abs($x)), $f);
$h = array_map(fn($x) => log1p(exp($x)), $g);
$i = array_slice($h, 1, 2);
$j = array_merge($i, $i);
$k = array_map(fn($x) => (int)round($x), $j);
$sum = array_sum($k);
$mean = $sum / count($k);
$max = max($k);
$min = min($k);
$id = "name_" . $counter++;
echo $id . "\n";
echo $sum . " " . $mean . " " . $max . " " . $min . "\n";
?>
--EXPECT--
