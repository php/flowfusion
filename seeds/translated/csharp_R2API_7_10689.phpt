--TEST--
Translated seed (inferredbugs-csharp: R2API/7)
--FILE--
<?php
class A { private int $x = 1; public static int $s = 2; private function p(): int { return $this->x; } private static function q(int $v): int { self::$s += $v; return self::$s; } }
class B extends A { private int $y = 3; private function r(): int { return $this->y; } }
$o = new B();
$rc = new ReflectionClass($o);
$rp = $rc->getProperty('x');
$rp->setAccessible(true);
$rp->setValue($o, 7);
$rv = $rp->getValue($o);
$rm = $rc->getMethod('p');
$rm->setAccessible(true);
$v1 = $rm->invoke($o);
$rcs = new ReflectionClass('A');
$rps = $rcs->getProperty('s');
$rps->setAccessible(true);
$rps->setValue(5);
$v2 = $rps->getValue();
$rms = $rcs->getMethod('q');
$rms->setAccessible(true);
$v3 = $rms->invoke(null, 4);
$rb = new ReflectionClass('B');
$ry = $rb->getProperty('y');
$ry->setAccessible(true);
$ry->setValue($o, 11);
$v4 = $ry->getValue($o);
$rr = $rb->getMethod('r');
$rr->setAccessible(true);
$v5 = $rr->invoke($o);
$sum = $rv + $v1 + $v2 + $v3 + $v4 + $v5;
echo $sum, PHP_EOL;
--EXPECT--
