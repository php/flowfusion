--TEST--
Translated seed (inferredbugs-csharp: FastReport/5)
--FILE--
<?php

class BarcodeAztec {
    private $errorCorrectionPercent;
    const PIXEL_SIZE = 4;
    private $matrix;

    public function __construct() {
        $this->errorCorrectionPercent = 33;
    }

    public function setErrorCorrectionPercent($value) {
        $this->errorCorrectionPercent = ($value < 5) ? 5 : (($value > 95) ? 95 : $value);
    }

    public function initialize($text) {
        $this->matrix = $this->encode($text, $this->errorCorrectionPercent);
    }

    private function encode($text, $errorCorrectionPercent) {
        // Simulate encoding into a matrix with dummy data
        $width = strlen($text); 
        $height = min(30, $width); // Simulate some height limit
        return new BitMatrix($width, $height);
    }

    public function calcBounds() {
        $textAdd = 18; // Assuming showText is true
        return [
            $this->matrix->getWidth() * self::PIXEL_SIZE,
            $this->matrix->getHeight() * self::PIXEL_SIZE + $textAdd
        ];
    }

    public function draw2DBarcode() {
        // Simulate drawing the barcode
        for ($y = 0; $y < $this->matrix->getHeight(); $y++) {
            for ($x = 0; $x < $this->matrix->getWidth(); $x++) {
                $b = $this->matrix->getRow($y)[$x];
                $color = $b ? 'black' : 'white';
                // Simulate drawing logic
            }
        }
    }
}

class BitMatrix {
    private $width;
    private $height;
    private $matrix;

    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
        $this->matrix = array_fill(0, $height, array_fill(0, $width, rand(0, 1)));
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getRow($y) {
        return $this->matrix[$y];
    }
}

$barcode = new BarcodeAztec();
$barcode->initialize("Sample Text");
$bounds = $barcode->calcBounds();
$barcode->draw2DBarcode();

?>
--EXPECT--
