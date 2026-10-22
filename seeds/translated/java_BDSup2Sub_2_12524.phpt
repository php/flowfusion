--TEST--
Translated seed (inferredbugs-java: BDSup2Sub/2)
--FILE--
<?php

class Core {
    private const MIN_IMAGE_DIMENSION = 8;

    private static $currentDVDPalette = [0, 0, 0, 255]; // Simulated palette values
    private static $subPictures = [];
    private static $cropOfsY = 0;

    public static function main() {
        self::initializeSubPictures();
        self::moveAllToBounds();
    }

    private static function initializeSubPictures() {
        for ($i = 0; $i < 10; $i++) {
            self::$subPictures[] = [
                'height' => rand(15, 50),
                'width' => rand(15, 50),
                'ofsX' => rand(0, 20),
                'ofsY' => rand(0, 20),
            ];
        }
    }

    private static function moveAllToBounds() {
        foreach (self::$subPictures as $index => &$pic) {
            self::moveToBounds($pic, $index + 1);
        }
    }

    private static function moveToBounds(&$pic, $idx) {
        $barHeight = (int)($pic['height'] * 0.1);
        $y1 = $pic['ofsY'];
        $y2 = $y1 + $pic['height'];

        if ($y1 < 50 && $y2 < 50) {
            $pic['ofsY'] = $barHeight; // Move up
        } else if ($y1 > 50 && $y2 > 50) {
            $pic['ofsY'] = 100 - $pic['height'] - $barHeight; // Move down
        } else {
            echo "Caption $idx not moved (too large)\n";
        }

        $yMax = 100 - $pic['height'] - self::$cropOfsY;
        if ($pic['ofsY'] > $yMax) {
            $pic['ofsY'] = $yMax; // Adjust the Y offset if it exceeds bounds
        }
        echo "Caption $idx moved to y position {$pic['ofsY']}\n";
    }
}

Core::main();
--EXPECT--
