--TEST--
Translated seed (inferredbugs-java: JOML/3)
--FILE--
<?php

class Vector4f {
    public float $x;
    public float $y;
    public float $z;
    public float $w;

    public function __construct(float $x = 0, float $y = 0, float $z = 0, float $w = 1) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->w = $w;
    }

    public function sub(Vector4f $v): Vector4f {
        $this->x -= $v->x;
        $this->y -= $v->y;
        $this->z -= $v->z;
        $this->w -= $v->w;
        return $this;
    }

    public function normalize(): Vector4f {
        $length = sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z + $this->w * $this->w);
        if ($length === 0) return $this;
        $this->x /= $length;
        $this->y /= $length;
        $this->z /= $length;
        $this->w /= $length;
        return $this;
    }

    public function dot(Vector4f $v): float {
        return $this->x * $v->x + $this->y * $v->y + $this->z * $v->z + $this->w * $v->w;
    }

    public function lengthSquared(): float {
        return $this->x * $this->x + $this->y * $this->y + $this->z * $this->z + $this->w * $this->w;
    }

    public function fma(Vector4f $a, Vector4f $b): Vector4f {
        $this->x += $a->x * $b->x;
        $this->y += $a->y * $b->y;
        $this->z += $a->z * $b->z;
        $this->w += $a->w * $b->w;
        return $this;
    }
}

$v1 = new Vector4f(1, 2, 3, 4);
$v2 = new Vector4f(4, 3, 2, 1);
$result = (new Vector4f())->fma($v1, $v2);
$result->normalize();

echo "Result: ({$result->x}, {$result->y}, {$result->z}, {$result->w})\n";
--EXPECT--
