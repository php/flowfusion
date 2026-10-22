--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/37)
--FILE--
<?php

class Region {
    private $x, $y, $w, $h;

    public function __construct($x, $y, $w, $h) {
        $this->x = $x;
        $this->y = $y;
        $this->w = max(1, $w);
        $this->h = max(1, $h);
    }

    public function getRect() {
        return [$this->x, $this->y, $this->w, $this->h];
    }

    public function add($l, $r, $t, $b) {
        $this->x -= $l;
        $this->y -= $t;
        $this->w += $l + $r;
        $this->h += $t + $b;
        $this->w = max(1, $this->w);
        $this->h = max(1, $this->h);
    }

    public function contains($point) {
        return $point[0] >= $this->x && $point[0] <= $this->x + $this->w &&
               $point[1] >= $this->y && $point[1] <= $this->y + $this->h;
    }

    public function intersection(Region $other) {
        $intersectX = max($this->x, $other->x);
        $intersectY = max($this->y, $other->y);
        $intersectW = min($this->x + $this->w, $other->x + $other->w) - $intersectX;
        $intersectH = min($this->y + $this->h, $other->y + $other->h) - $intersectY;

        if ($intersectW > 0 && $intersectH > 0) {
            return new Region($intersectX, $intersectY, $intersectW, $intersectH);
        }
        return null;
    }
}

$region = new Region(-10, -10, 100, 100);
$region->add(20, 20, 20, 20);
$other = new Region(0, 0, 50, 50);
$intersection = $region->intersection($other);
if ($intersection) {
    echo "Intersection: " . json_encode($intersection->getRect());
} else {
    echo "No intersection.";
}
--EXPECT--
