--TEST--
Translated seed (inferredbugs-csharp: c-raft/1)
--FILE--
<?php
class V3 {
    public float $x;
    public float $y;
    public float $z;
    function __construct($x, $y, $z) { $this->x = $x; $this->y = $y; $this->z = $z; }
}
class P {
    public V3 $v;
    public float $yaw = 0.0;
    public float $pitch = 0.0;
    function __construct() { $this->v = new V3(0.0, 0.0, 0.0); }
    function __get($n) { return $n === 'packedYaw' ? (int)($this->yaw * 256 / 360) : (int)($this->pitch * 256 / 360); }
}
class E {
    public int $id;
    public P $pos;
    function __construct($id) { $this->id = $id; $this->pos = new P(); }
}
$e = new E(1);
$e->pos->v->x = 1.25;
$e->pos->v->y = -2.5;
$e->pos->v->z = 3.75;
$e->pos->yaw = 361.0;
$e->pos->pitch = -91.0;
$dx = (int)(32 * (4.0 - $e->pos->v->x));
$dy = (int)(32 * (5.0 - $e->pos->v->y));
$dz = (int)(32 * (6.0 - $e->pos->v->z));
$e->pos->v = new V3(4.0, 5.0, 6.0);
$e->pos->yaw = 720.5;
$e->pos->pitch = 181.25;
$a = $e->pos->packedYaw;
$b = $e->pos->packedPitch;
$e->pos->v->x += $dx / 32.0;
$e->pos->v->y += $dy / 32.0;
$e->pos->v->z += $dz / 32.0;
echo "";
?>
--EXPECT--
