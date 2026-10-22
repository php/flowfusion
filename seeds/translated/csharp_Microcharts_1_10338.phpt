--TEST--
Translated seed (inferredbugs-csharp: Microcharts/1)
--FILE--
<?php

class Rect {
    public float $x;
    public float $y;
    public float $width;
    public float $height;
    public function __construct(float $x, float $y, float $width, float $height) {
        $this->x = $x; $this->y = $y; $this->width = $width; $this->height = $height;
    }
}

class Chart {
    public function draw($canvas, int $width, int $height): void {
        $canvas[] = [$width, $height];
    }
}

$boundsGetter = fn() => new Rect(1.9, 2.1, 0.0, 0.0);
$chartGetter = fn() => new Chart();

$bounds = $boundsGetter();
$chart = $chartGetter();
$width = (int)$bounds->width;
$height = (int)$bounds->height;

$bitmap = array_fill(0, max(0, $height), array_fill(0, max(0, $width), 0));
$canvas = [];
$chart?->draw($canvas, $width, $height);

$drawX = (int)$bounds->x;
$drawY = (int)$bounds->y;
$dest = [$drawX, $drawY, $bitmap, $canvas];

echo "";
?>
--EXPECT--
