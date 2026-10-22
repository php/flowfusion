--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/48)
--FILE--
<?php

class Region {
    public $x;
    public $y;
    public $w;
    public $h;

    public function __construct($x, $y, $w, $h) {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w > 1 ? $w : 1;
        $this->h = $h > 1 ? $h : 1;
    }

    public function isValid() {
        return $this->w > 0 && $this->h > 0;
    }

    public function contains($pointX, $pointY) {
        return $pointX >= $this->x && $pointX < $this->x + $this->w && $pointY >= $this->y && $pointY < $this->y + $this->h;
    }
}

function main() {
    $region = new Region(0, 0, 10, 10);
    $outsidePointX = 11;
    $outsidePointY = 5;
    $insidePointX = 5;
    $insidePointY = 5;

    $validityCheck = $region->isValid(); // region is valid
    $containsOutside = $region->contains($outsidePointX, $outsidePointY); // should be false
    $containsInside = $region->contains($insidePointX, $insidePointY); // should be true

    // To output the results
    echo "Region valid: " . ($validityCheck ? 'True' : 'False') . "\n";
    echo "Contains outside point: " . ($containsOutside ? 'True' : 'False') . "\n";
    echo "Contains inside point: " . ($containsInside ? 'True' : 'False') . "\n";
}

main();
--EXPECT--
