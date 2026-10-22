--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/43)
--FILE--
<?php

class EditorRegionButton {
    private $x, $y, $w, $h;

    public function __construct($x, $y, $w, $h) {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
        $this->createImage();
    }

    private function createImage() {
        $image = imagecreatetruecolor($this->w, $this->h);
        $red = imagecolorallocate($image, 255, 0, 0);
        imagefilledrectangle($image, 0, 0, $this->w - 1, $this->h - 1, $red);
        imagepng($image, 'region.png');
        imagedestroy($image);
    }

    public static function createFromString($str) {
        preg_match('/\((\d+), (\d+), (\d+), (\d+)\)/', $str, $matches);
        if (count($matches) === 5) {
            return new EditorRegionButton((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[4]);
        }
        return null;
    }

    public function __toString() {
        return sprintf("Region(%d,%d,%d,%d)", $this->x, $this->y, $this->w, $this->h);
    }
}

$x = 10;
$y = 20;
$w = 30;
$h = 40;

$button = new EditorRegionButton($x, $y, $w, $h);
$stringRepresentation = $button->__toString();
$buttonFromString = EditorRegionButton::createFromString($stringRepresentation);
--EXPECT--
