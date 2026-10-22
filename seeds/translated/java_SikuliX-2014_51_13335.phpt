--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/51)
--FILE--
<?php

class Region {
    public $x;
    public $y;
    public $w;
    public $h;
    
    public function __construct($X, $Y, $W, $H) {
        $this->x = $X;
        $this->y = $Y;
        $this->w = max(1, $W);
        $this->h = max(1, $H);
    }

    public function isValid() {
        return $this->w > 0 && $this->h > 0;
    }

    public function contains($pointX, $pointY) {
        return $pointX >= $this->x && $pointX < $this->x + $this->w &&
               $pointY >= $this->y && $pointY < $this->y + $this->h;
    }

    public function intersect(Region $other) {
        $newX = max($this->x, $other->x);
        $newY = max($this->y, $other->y);
        $newW = min($this->x + $this->w, $other->x + $other->w) - $newX;
        $newH = min($this->y + $this->h, $other->y + $other->h) - $newY;

        return new Region($newX, $newY, $newW, $newH);
    }

    public function add($region) {
        $this->x = min($this->x, $region->x);
        $this->y = min($this->y, $region->y);
        $this->w = max($this->x + $this->w, $region->x + $region->w) - $this->x;
        $this->h = max($this->y + $this->h, $region->y + $region->h) - $this->y;
    }

    public function __toString() {
        return sprintf("R[%d,%d %dx%d]", $this->x, $this->y, $this->w, $this->h);
    }
}

$region1 = new Region(-10, -10, 50, 50);
$region2 = new Region(0, 0, 50, 50);
$intersection = $region1->intersect($region2);
$region1->add($region2);

echo $region1 . "\n"; 
echo $intersection . "\n"; 
echo $region1->contains(5, 5) ? "Contains" : "Does not contain"; 
echo "\n";
echo $intersection->isValid() ? "Intersection Valid" : "Intersection Not Valid"; 
echo "\n";
--EXPECT--
