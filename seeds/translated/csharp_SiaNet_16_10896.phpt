--TEST--
Translated seed (inferredbugs-csharp: SiaNet/16)
--FILE--
<?php
$counter = 0;
$a = [1.0, -2.5, 0.0, 3.75];
$b = [0.5, 4.0, -1.0, 2.0];

$r = array_map(fn($x) => abs($x), $a);
$r = array_map(fn($x) => acos(max(-1.0, min(1.0, $x / 4.0))), $r);
$r = array_map(fn($x, $y) => $x + $y, $r, $b);
$r = array_map(fn($x) => atan2($x, 1.5), $r);
$r = array_map(fn($x) => ceil($x), $r);
$r = array_map(fn($x) => min(2.0, max(-1.0, $x)), $r);
$r = array_map(fn($x) => cos($x), $r);
$r = array_map(fn($x) => cosh($x), $r);
$r = array_map(fn($x) => exp($x), $r);
$r = array_map(fn($x) => floor($x), $r);
$r = array_map(fn($x) => log1p(abs($x)), $r);
$r = array_map(fn($x) => round($x), $r);
$r = array_map(fn($x) => sin($x), $r);
$r = array_map(fn($x) => sinh($x), $r);
$r = array_map(fn($x) => sqrt(abs($x)), $r);
$r = array_map(fn($x) => square($x), $r);
$r = array_map(fn($x) => tan($x), $r);
$r = array_map(fn($x) => tanh($x), $r);

$mat = [[1, 2, 3], [4, 5, 6]];
$mat = array_map(fn($row) => array_map(fn($x) => $x * 2, $row), $mat);
$mat = array_reverse($mat);
$mat = array_map(null, ...$mat);

$sum = array_sum($r);
$mean = $sum / max(1, count($r));
$max = max($r);
$min = min($r);

$shape = [count($mat), count($mat[0])];
$slice = array_slice($mat, 0, 1);
$tile = array_merge($slice, $slice);

$uuid = "name_" . $counter++;
echo $uuid . PHP_EOL;
echo $sum . " " . $mean . " " . $max . " " . $min . PHP_EOL;
echo count($tile) . "x" . count($tile[0]) . PHP_EOL;
function square($x) { return $x * $x; }
?>
--EXPECT--
