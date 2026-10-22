--TEST--
Translated seed (inferredbugs-csharp: opengl4csharp/2)
--FILE--
<?php
declare(strict_types=1);

$location = [1.5, -2.0];
$size = [3.25, 4.75];
$uvloc = [0.125, 0.25];
$uvsize = [0.5, 0.75];
$min = [-1.0, -2.0, -3.0];
$max = [4.0, 5.0, 6.0];

$vertices = [
    [$location[0], $location[1], 0.0],
    [$location[0] + $size[0], $location[1], 0.0],
    [$location[0] + $size[0], $location[1] + $size[1], 0.0],
    [$location[0], $location[1] + $size[1], 0.0],
];
$uvs = [
    [0.0, 0.0],
    [1.0, 0.0],
    [1.0, 1.0],
    [0.0, 1.0],
];
$indices = [0, 1, 2, 2, 3, 0];

$vertices2 = [
    [$location[0], $location[1], 0.0],
    [$location[0] + $size[0], $location[1], 0.0],
    [$location[0] + $size[0], $location[1] + $size[1], 0.0],
    [$location[0], $location[1] + $size[1], 0.0],
];
$uvs2 = [
    [$uvloc[0], $uvloc[1]],
    [$uvloc[0] + $uvsize[0], $uvloc[1]],
    [$uvloc[0] + $uvsize[0], $uvloc[1] + $uvsize[1]],
    [$uvloc[0], $uvloc[1] + $uvsize[1]],
];
$indices2 = [0, 1, 2, 2, 3, 0];

$cubeVertices = [
    [$min[0], $min[1], $max[2]],
    [$max[0], $min[1], $max[2]],
    [$min[0], $max[1], $max[2]],
    [$max[0], $max[1], $max[2]],
    [$max[0], $min[1], $min[2]],
    [$max[0], $max[1], $min[2]],
    [$min[0], $max[1], $min[2]],
    [$min[0], $min[1], $min[2]],
];
$cubeElements = [
    0, 1, 2, 1, 3, 2,
    1, 4, 3, 4, 5, 3,
    4, 7, 5, 7, 6, 5,
    7, 0, 6, 0, 2, 6,
    7, 4, 0, 4, 1, 0,
    2, 3, 6, 3, 5, 6,
];

echo count($vertices) + count($uvs) + count($indices) + count($vertices2) + count($uvs2) + count($indices2) + count($cubeVertices) + count($cubeElements), PHP_EOL;
?>
--EXPECT--
