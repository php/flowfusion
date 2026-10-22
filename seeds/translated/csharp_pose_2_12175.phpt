--TEST--
Translated seed (inferredbugs-csharp: pose/2)
--FILE--
<?php
class Box {
    public $x = 7;
    public static $y = 11;
    public function getX() { return $this->x; }
    public static function getY() { return self::$y; }
}

$o = new Box();
$r = new ReflectionProperty(Box::class, 'x');
$r->setAccessible(true);
$v1 = $r->getValue($o);

$rs = new ReflectionProperty(Box::class, 'y');
$rs->setAccessible(true);
$v2 = $rs->getValue();

$rm1 = new ReflectionMethod(Box::class, 'getX');
$rm2 = new ReflectionMethod(Box::class, 'getY');

$s1 = ($rm1->isStatic() ? '' : Box::class) . ' (' . implode(', ', array_map(fn($p) => (string)$p->getType(), $rm1->getParameters())) . ')';
$s2 = ($rm2->isStatic() ? '' : Box::class) . ' (' . implode(', ', array_map(fn($p) => (string)$p->getType(), $rm2->getParameters())) . ')';

$call1 = $rm1->invoke($o);
$call2 = $rm2->invoke(null);

$mix = [$v1, $v2, $call1, $call2, $s1, $s2];
echo implode('|', array_map('strval', $mix));
?>
--EXPECT--
