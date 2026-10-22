--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/47)
--FILE--
<?php

class Region {
    public $x, $y, $w, $h;

    public function __construct($X, $Y, $W, $H) {
        $this->x = $X;
        $this->y = $Y;
        $this->w = ($W > 1) ? $W : 1;
        $this->h = ($H > 1) ? $H : 1;
    }

    public function add($l, $r, $t, $b) {
        $this->x -= $l;
        $this->y -= $t;
        $this->w += ($l + $r);
        $this->h += ($t + $b);
        if ($this->w < 1) {
            $this->w = 1;
        }
        if ($this->h < 1) {
            $this->h = 1;
        }
        return $this;
    }

    public function getRect() {
        return [$this->x, $this->y, $this->w, $this->h];
    }

    public function contains($point) {
        return $this->x <= $point[0] && $point[0] <= $this->x + $this->w &&
               $this->y <= $point[1] && $point[1] <= $this->y + $this->h;
    }

    public function isValid() {
        return $this->w > 0 && $this->h > 0;
    }
}

$region = new Region(-5, -5, 10, 10);
$region->add(5, 5, 5, 5);
$rect = $region->getRect();
var_dump($rect); // To see the updated rectangle

$point = [0, 0];
var_dump($region->contains($point)); // Check if point is contained
--EXPECT--
