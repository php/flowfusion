--TEST--
Translated seed (inferredbugs-csharp: Eto/13)
--FILE--
<?php

class GraphicsHandler {
    private $height;
    private $viewFlipped;

    public function __construct($height, $flipped) {
        $this->height = $height;
        $this->viewFlipped = $flipped;
    }

    public function translateView($point) {
        if (!$this->viewFlipped) {
            $point[1] = $this->height - $point[1];
        }
        return $point;
    }

    public function drawLine($color, $startX, $startY, $endX, $endY) {
        $startPoint = $this->translateView([$startX, $startY]);
        $endPoint = $this->translateView([$endX, $endY]);
        // Simulating draw line
        echo "Drawing line from (" . implode(", ", $startPoint) . ") to (" . implode(", ", $endPoint) . ") in color $color\n";
    }

    public function drawRectangle($color, $x, $y, $width, $height) {
        $rectStart = $this->translateView([$x, $y]);
        // Adjust rectangle based on translation
        $rectStart[0] += 0.5;
        $rectStart[1] += 0.5;
        $width -= 1;
        $height -= 1;
        // Simulating draw rectangle
        echo "Drawing rectangle at (" . implode(", ", $rectStart) . ") with width $width and height $height in color $color\n";
    }

    public function drawText($font, $color, $x, $y, $text) {
        $point = $this->translateView([$x, $y]);
        // Simulating draw text
        echo "Drawing text '$text' at (" . implode(", ", $point) . ") in color $color with font $font\n";
    }
}

function main() {
    $graphics = new GraphicsHandler(300, false);
    $graphics->drawLine('red', 10, 10, 100, 100);
    $graphics->drawRectangle('blue', 50, 50, 200, 100);
    $graphics->drawText('Arial', 'black', 60, 60, 'Hello World');
}

main();
--EXPECT--
