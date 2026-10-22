--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/50)
--FILE--
<?php

class Region {
    public $x, $y, $w, $h;
    private $scr;

    public function __construct($X, $Y, $W, $H) {
        $this->x = $X;
        $this->y = $Y;
        $this->w = max(1, $W);
        $this->h = max(1, $H);
        $this->initScreen();
    }

    private function initScreen() {
        if ($this->w <= 0 || $this->h <= 0) {
            $this->scr = null;
            return;
        }
        $screenRect = ['x' => 0, 'y' => 0, 'width' => 800, 'height' => 600];
        $this->scr = $screenRect; // Simulated screen
        $this->x = max(0, $this->x);
        $this->y = max(0, $this->y);
    }

    public function isValid() {
        return $this->scr !== null && $this->w > 0 && $this->h > 0;
    }

    public function grow($width, $height) {
        $this->x -= $width;
        $this->y -= $height;
        $this->w += 2 * $width;
        $this->h += 2 * $height;
        $this->initScreen();
    }

    public function setRect($X, $Y, $W, $H) {
        $this->x = $X;
        $this->y = $Y;
        $this->w = max(1, $W);
        $this->h = max(1, $H);
        $this->initScreen();
    }

    public function contains($pointX, $pointY) {
        return $pointX >= $this->x && $pointX < $this->x + $this->w && 
               $pointY >= $this->y && $pointY < $this->y + $this->h;
    }

    public function toString() {
        return sprintf("R[%d,%d %dx%d]", $this->x, $this->y, $this->w, $this->h);
    }
}

$region = new Region(10, 10, 100, 100);
$region->grow(5, 5);
echo $region->toString(); // Output: R[5,5 110x110]
echo $region->isValid() ? " yes" : " no"; // Output: yes
echo $region->contains(50, 50) ? " contains (50, 50)" : " does not contain"; // Output: contains (50, 50)
--EXPECT--
