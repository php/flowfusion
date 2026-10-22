--TEST--
Translated seed (inferredbugs-csharp: rush/2)
--FILE--
<?php
mt_srand();

$explosionColour = [255, 255, 255, 255];
$sparkCount = 10;
$sparkColour = [255, 255, 255, 255];

$scale = 0.9 + mt_rand() / mt_getrandmax() * 0.2;
$rotation = mt_rand() / mt_getrandmax() * 360.0;

$triangles = [];
for ($i = 0; $i < $sparkCount; $i++) {
    $offset = (mt_rand() / mt_getrandmax()) * 2.0 / $sparkCount;
    $rotationDeg = (($i / $sparkCount) + $offset) * 360.0;
    $triangles[] = $rotationDeg;
}

foreach ($triangles as $rotationDeg) {
    $sparkScale = 0.8 + mt_rand() / mt_getrandmax() * 0.2;
    $duration = 1500.0 * (0.8 + mt_rand() / mt_getrandmax() * 0.4);
    $radians = deg2rad($rotationDeg + 90.0);
    $distance = 200.0 * (0.8 + mt_rand() / mt_getrandmax() * 0.2);
    $targetX = cos($radians) * $distance;
    $targetY = sin($radians) * $distance;
}

echo "";
?>
--EXPECT--
