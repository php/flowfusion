--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/27)
--FILE--
<?php

class Image {
    private static $images = [];
    private static $currentMemory = 0;
    private static $maxMemory = 1024 * 1024; // 1 MB for cache example
    private $bimg = null;
    private $bsize = 0;
    private $imageName;

    public function __construct($name) {
        $this->imageName = $name;
        $this->load();
    }

    private function load() {
        // Simulate loading an image and its size
        $this->bimg = imagecreatetruecolor(100, 100); // Placeholder for an actual image
        $this->bsize = 100 * 100 * 4; // 4 bytes per pixel
        $this->cacheImage();
    }

    private function cacheImage() {
        if (self::$currentMemory + $this->bsize > self::$maxMemory) {
            $this->clearCache();
        }
        self::$currentMemory += $this->bsize;
        self::$images[$this->imageName] = $this;
    }

    private function clearCache() {
        array_shift(self::$images); // Remove the first image
        self::$currentMemory -= $this->bsize; // Adjust current memory
    }

    public function __destruct() {
        imagedestroy($this->bimg);
    }
}

new Image("test_image");
--EXPECT--
