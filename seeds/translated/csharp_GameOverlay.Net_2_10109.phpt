--TEST--
Translated seed (inferredbugs-csharp: GameOverlay.Net/2)
--FILE--
<?php
$brushes = [];
$fonts = [];
$images = [];

$gfx = new stdClass();
$gfx->MeasureFPS = true;
$gfx->PerPrimitiveAntiAliasing = true;
$gfx->TextAntiAliasing = true;
$gfx->UseMultiThreadedFactories = false;
$gfx->VSync = false;
$gfx->WindowHandle = 0;
$gfx->FPS = 60;
$gfx->Width = 1280;
$gfx->Height = 720;

$brushes["black"] = [0, 0, 0];
$brushes["white"] = [255, 255, 255];
$brushes["background"] = [0, 39, 49, 204];

$fonts["arial"] = ["Arial", 14];

$scene = [];
$scene[] = ["clear", $brushes["background"]];
$scene[] = ["text", "FPS: " . $gfx->FPS, 22, $brushes["white"], 20, 20];

$geometry = [
    "fillMode" => "winding",
    "figure" => [
        [200.0, 200.0],
        [300.0, 200.0],
        ["arc", [300.0, 300.0], 16.0, 16.0, 0.0, "small", "clockwise"],
        [200.0, 300.0],
        ["arc", [200.0, 200.0], 16.0, 16.0, 0.0, "small", "clockwise"],
    ],
    "end" => "open",
];

$layer = [
    "mask" => $geometry,
    "size" => [$gfx->Width, $gfx->Height],
];

$scene[] = ["pushLayer", $layer];
$scene[] = ["fillRect", $brushes["white"], 100, 100, 400, 400];
$scene[] = ["popLayer"];

echo "";
?>
--EXPECT--
