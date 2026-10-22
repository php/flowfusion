--TEST--
Translated seed (inferredbugs-csharp: Eto/5)
--FILE--
<?php

class GraphicsHandler {
    private $context;
    private $height;
    private $flipped;

    public function __construct($height) {
        $this->height = $height;
        $this->flipped = false;
    }

    private function translatePoint($x, $y) {
        if (!$this->flipped) {
            $y = $this->height - $y;
        }
        return [$x, $y];
    }

    private function drawLine($startX, $startY, $endX, $endY) {
        $start = $this->translatePoint($startX, $startY);
        $end = $this->translatePoint($endX, $endY);
        echo "Drawing line from (" . $start[0] . ", " . $start[1] . ") to (" . $end[0] . ", " . $end[1] . ")\n";
    }

    private function drawRectangle($x, $y, $width, $height) {
        $topLeft = $this->translatePoint($x, $y);
        $bottomRight = $this->translatePoint($x + $width, $y + $height);
        echo "Drawing rectangle from (" . $topLeft[0] . ", " . $topLeft[1] . ") to (" . $bottomRight[0] . ", " . $bottomRight[1] . ")\n";
    }

    public function execute() {
        $this->drawLine(10, 10, 100, 50);
        $this->drawRectangle(20, 30, 150, 75);
    }
}

$handler = new GraphicsHandler(100);
$handler->execute();
--EXPECT--
