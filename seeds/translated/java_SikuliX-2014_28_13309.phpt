--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/28)
--FILE--
<?php
class Image {
    private static $images = [];
    private static $currentMemory = 0;
    private static $maxMemory = 1024 * 1024; // 1 MB
    private $bsize = 0;
    private $bimg = null;

    public function __construct($width, $height) {
        $this->bimg = imagecreatetruecolor($width, $height);
        $this->bsize = $width * $height * 4; // Approximation for RGBA
        self::$currentMemory += $this->bsize;
        if (self::$currentMemory > self::$maxMemory) {
            $this->clearCache();
        }
    }

    public function clearCache() {
        while (self::$currentMemory > self::$maxMemory) {
            array_pop(self::$images);
            self::$currentMemory -= $this->bsize;
        }
    }

    public function get() {
        return $this->bimg;
    }
}

$image = new Image(512, 512);
$imageResource = $image->get();
imagefill($imageResource, 0, 0, 0xFFFFFF); // Fill white
imagepng($imageResource, 'output.png');
imagedestroy($imageResource);
--EXPECT--
