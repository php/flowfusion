--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/18)
--FILE--
<?php
$x1 = range(0, 9);
$x2 = range(0, 19);
$x3 = array_slice($x2, 0, 10);
$x4 = array_slice($x2, 10, 10);

$shape1 = [count($x1)];
$shape2 = [count($x2)];
$shape3 = [count($x3)];

$storage = array_fill(0, 20, 0.0);
$y1 = &$storage;
$y2 = &$storage;
$y3 = &$storage;

for ($i = 0; $i < 10; $i++) {
    $x1[$i] = $i * 47.11;
}
$x1 = array_chunk($x1, 10)[0];
$x1 = array_merge($x1);

$mat = [];
for ($i = 0; $i < 3; $i++) {
    for ($j = 0; $j < 3; $j++) $mat[$i][$j] = $i * 3 + $j + 1;
}
$diag0 = [$mat[0][0], $mat[1][1], $mat[2][2]];
$diag1 = [$mat[0][1], $mat[1][2]];

$a = range(1, 9);
$b = range(10, 18);
$c = array_merge($a, $b);

$eye = [];
for ($i = 0; $i < 10; $i++) {
    for ($j = 0; $j < 10; $j++) $eye[$i][$j] = $i === $j ? 1 : 0;
}

$r1 = [];
for ($i = 0; $i < 100; $i++) $r1[] = $i;
$r2 = [];
for ($i = 0; $i < 30; $i++) $r2[] = 5 * $i;

$z = range(0, 9);
$w = &$z;
$w[5] = 0;

$grid = [];
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 10; $j++) $grid[$i][$j] = $i + $j;
}
$grid2 = [];
for ($i = 0; $i < 10; $i++) {
    for ($j = 0; $j < 5; $j++) $grid2[$i][$j] = $grid[intdiv($i, 2)][($i % 2) * 5 + $j];
}

$cube = array_fill(0, 10, array_fill(0, 10, array_fill(0, 10, 1)));
$flat = [];
for ($i = 0; $i < 10; $i++) for ($j = 0; $j < 10; $j++) for ($k = 0; $k < 10; $k++) $flat[] = $cube[$i][$j][$k];
$flat[5] = 0;

$big = array_fill(0, 10, array_fill(0, 10, array_fill(0, 10, array_fill(0, 5, 1))));
$bigflat = [];
for ($i = 0; $i < 10; $i++) for ($j = 0; $j < 10; $j++) for ($k = 0; $k < 10; $k++) for ($l = 0; $l < 5; $l++) $bigflat[] = $big[$i][$j][$k][$l];
$bigflat[567] = 0;

$d1 = range(0, 9);
$d2 = range(0, 19);
$d3 = array_slice($d2, 0, 10);

echo "";
?>
--EXPECT--
