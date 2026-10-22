--TEST--
Translated seed (inferredbugs-csharp: GameOverlay.Net/1)
--FILE--
<?php
$brushes = [];
$fonts = [];
$images = [];

$brushes["black"] = [0, 0, 0, 255];
$brushes["white"] = [255, 255, 255, 255];
$brushes["background"] = [0, 39, 49, (int)(255.0 * 0.8)];

$fonts["arial"] = ["Arial", 14];

$fps = 60;
$scene = $brushes["background"];
$text = "FPS: " . $fps;
$rect = [20, 60, 400, 400, 1.0];

$scene[0] = $scene[0];
$text = $text;
$rect[4] = $rect[4];

unset($brushes, $fonts, $images);
?>
--EXPECT--
