--TEST--
Translated seed (inferredbugs-java: JOML/1)
--FILE--
<?php

class Quaternion {
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

    public function normalize(): self {
        $invNorm = 1.0 / sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z + $this->w * $this->w);
        $this->x *= $invNorm;
        $this->y *= $invNorm;
        $this->z *= $invNorm;
        $this->w *= $invNorm;
        return $this;
    }

    public function rotate(float $angleX, float $angleY, float $angleZ): self {
        $thetaX = $angleX * 0.5;
        $thetaY = $angleY * 0.5;
        $thetaZ = $angleZ * 0.5;

        $sx = sin($thetaX);
        $sy = sin($thetaY);
        $sz = sin($thetaZ);
        $cx = cos($thetaX);
        $cy = cos($thetaY);
        $cz = cos($thetaZ);

        $this->w = $cx * $cy * $cz - $sx * $sy * $sz;
        $this->x = $sx * $cy * $cz + $cx * $sy * $sz;
        $this->y = $cx * $sy * $cz - $sx * $cy * $sz;
        $this->z = $cx * $cy * $sz + $sx * $sy * $cz;

        return $this;
    }
}

$quat = new Quaternion();
$quat->rotate(M_PI / 2, M_PI / 2, M_PI / 2);
$quat->normalize();
--EXPECT--
