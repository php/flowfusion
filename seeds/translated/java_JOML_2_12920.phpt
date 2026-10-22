--TEST--
Translated seed (inferredbugs-java: JOML/2)
--FILE--
<?php
class Vector3 {
    public float $x;
    public float $y;
    public float $z;

    public function __construct(float $x = 0, float $y = 0, float $z = 0) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function add(Vector3 $v): Vector3 {
        $this->x += $v->x;
        $this->y += $v->y;
        $this->z += $v->z;
        return $this;
    }

    public function sub(Vector3 $v): Vector3 {
        $this->x -= $v->x;
        $this->y -= $v->y;
        $this->z -= $v->z;
        return $this;
    }

    public function mul(float $scalar): Vector3 {
        $this->x *= $scalar;
        $this->y *= $scalar;
        $this->z *= $scalar;
        return $this;
    }

    public function div(float $scalar): Vector3 {
        if ($scalar != 0) {
            $this->x /= $scalar;
            $this->y /= $scalar;
            $this->z /= $scalar;
        }
        return $this;
    }
    
    public function normalize(): Vector3 {
        $length = sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z);
        if ($length > 0) {
            $this->x /= $length;
            $this->y /= $length;
            $this->z /= $length;
        }
        return $this;
    }

    public function length(): float {
        return sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z);
    }

    public function dot(Vector3 $v): float {
        return $this->x * $v->x + $this->y * $v->y + $this->z * $v->z;
    }

    public function cross(Vector3 $v): Vector3 {
        return new Vector3(
            $this->y * $v->z - $this->z * $v->y,
            $this->z * $v->x - $this->x * $v->z,
            $this->x * $v->y - $this->y * $v->x
        );
    }
}

$v1 = new Vector3(1.0, 2.0, 3.0);
$v2 = new Vector3(4.0, 5.0, 6.0);
$result = $v1->add($v2)->normalize()->cross($v2);
echo "Result: ($result->x, $result->y, $result->z)\n";
--EXPECT--
