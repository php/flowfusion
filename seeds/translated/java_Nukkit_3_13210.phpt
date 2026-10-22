--TEST--
Translated seed (inferredbugs-java: Nukkit/3)
--FILE--
<?php

class AxisAlignedBB {
    public function __construct($x1, $y1, $z1, $x2, $y2, $z2) {}
}

class FenceGate {
    private $meta;

    const FENCE_GATE = 0; // Placeholder for identifier
    private $x = 0, $y = 0, $z = 0; // Placeholder for coordinates

    public function __construct($meta = 0) {
        $this->meta = $meta;
    }

    public function getDamage() {
        return $this->meta;
    }

    protected function recalculateBoundingBox() {
        if (($this->getDamage() & 0x04) > 0) {
            return null;
        }
        $i = $this->getDamage() & 0x03;
        if ($i == 2 || $i == 0) {
            return new AxisAlignedBB(
                $this->x,
                $this->y,
                $this->z + 0.375,
                $this->x + 1,
                $this->y + 1.5,
                $this->z + 0.625
            );
        } else {
            return new AxisAlignedBB(
                $this->x + 0.375,
                $this->y,
                $this->z,
                $this->x + 0.625,
                $this->y + 1.5,
                $this->z + 1
            );
        }
    }

    public function activate($direction) {
        $faces = [3, 0, 1, 2];
        $this->meta = ($faces[$direction & 3]) | (($this->meta ^ 0x04) & 0x04);
        $this->recalculateBoundingBox();
    }
}

$gate = new FenceGate();
$gate->activate(1); // Simulating activation with a direction of 1
$gate->recalculateBoundingBox();
--EXPECT--
