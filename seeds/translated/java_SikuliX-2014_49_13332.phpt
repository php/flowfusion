--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/49)
--FILE--
<?php

class Region {
    public $x;
    public $y;
    public $w;
    public $h;

    public static function create($x, $y, $w, $h) {
        $region = new self();
        $region->x = $x;
        $region->y = $y;
        $region->w = max($w, 1);
        $region->h = max($h, 1);
        return $region;
    }

    public function isValid() {
        return $this->w > 0 && $this->h > 0;
    }

    public function add($l, $r, $t, $b) {
        $this->x -= $l;
        $this->y -= $t;
        $this->w += $l + $r;
        $this->h += $t + $b;
        $this->w = max($this->w, 1);
        $this->h = max($this->h, 1);
    }

    public function intersects($other) {
        return !($this->x >= $other->x + $other->w || 
                  $this->x + $this->w <= $other->x || 
                  $this->y >= $other->y + $other->h || 
                  $this->y + $this->h <= $other->y);
    }

    public function getIntersection($other) {
        if (!$this->intersects($other)) {
            return null;
        }
        $intersectX = max($this->x, $other->x);
        $intersectY = max($this->y, $other->y);
        $intersectW = min($this->x + $this->w, $other->x + $other->w) - $intersectX;
        $intersectH = min($this->y + $this->h, $other->y + $other->h) - $intersectY;
        return self::create($intersectX, $intersectY, $intersectW, $intersectH);
    }
}

$region1 = Region::create(0, 0, 10, 10);
$region2 = Region::create(5, 5, 10, 10);
$intersection = $region1->getIntersection($region2);

if ($intersection) {
    echo "Intersection Region: x={$intersection->x}, y={$intersection->y}, w={$intersection->w}, h={$intersection->h}\n";
} else {
    echo "No Intersection\n";
}
--EXPECT--
