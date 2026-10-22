--TEST--
Translated seed (inferredbugs-csharp: Tizen-CSharp-Samples/7)
--FILE--
<?php
$types = ["Image","Animated","Color","NPatch","Border","Gradient","SVG","Cone","Sphere","Cylinder","ConicalFrustrum","Cube","Octahedron","BevelledCube"];
$index = 0;
$touched = false;
$startX = 0;
$startY = 0;

$events = [
    ["Down", 100, 100],
    ["Motion", 140, 105],
    ["Down", 200, 200],
    ["Motion", 160, 198],
    ["Down", 300, 300],
    ["Motion", 290, 330],
    ["Motion", 260, 301],
    ["Down", 400, 400],
    ["Motion", 450, 399],
];

foreach ($events as $e) {
    $state = $e[0];
    $x = $e[1];
    $y = $e[2];
    if ($state === "Down") {
        $startX = $x;
        $startY = $y;
        $touched = true;
        continue;
    }
    if (!$touched) {
        continue;
    }
    $dx = $x - $startX;
    $dy = $y - $startY;
    if (abs($dy) > 20) {
        $touched = false;
        continue;
    }
    if ($dx > 30) {
        if ($index > 0) {
            $index--;
        }
        $touched = false;
    }
    if ($dx < -30) {
        if ($index < count($types) - 1) {
            $index++;
        }
        $touched = false;
    }
}

echo $types[$index], PHP_EOL;
?>
--EXPECT--
