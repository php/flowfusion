--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/46)
--FILE--
<?php

class Region {
    private $x;
    private $y;
    private $w;
    private $h;

    public function __construct($X, $Y, $W, $H) {
        $this->x = $X;
        $this->y = $Y;
        $this->w = max(1, $W);
        $this->h = max(1, $H);
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

    public function contains($px, $py) {
        return $px >= $this->x && $px <= $this->x + $this->w && $py >= $this->y && $py <= $this->y + $this->h;
    }
}

$region = new Region(0, 0, 5, 5);
$region->add(2, 2, 2, 2);
$rect = $region->getRect();
$isInRegion = $region->contains(1, 1);

echo "Region rectangle: (" . implode(",", $rect) . ")\n";
echo "Point (1, 1) is inside region: " . ($isInRegion ? "true" : "false") . "\n";
--EXPECT--
