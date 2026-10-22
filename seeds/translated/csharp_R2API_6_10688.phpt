--TEST--
Translated seed (inferredbugs-csharp: R2API/6)
--FILE--
<?php
class B {
    private int $x = 1;
    protected static int $s = 2;
    private function inc(int $a): int { return $this->x + $a; }
    private static function mul(int $a, int $b): int { return $a * $b; }
}
class C extends B {
    private int $y = 3;
    private function sum(int $a, int $b): int { return $a + $b + $this->y; }
}
$o = new C();
$r = new ReflectionClass($o);
$p = $r->getProperty('y');
$p->setAccessible(true);
$v = $p->getValue($o);
$p->setValue($o, $v + 4);
$m = $r->getMethod('sum');
$m->setAccessible(true);
$x = $m->invokeArgs($o, [1, 2]);
$rb = new ReflectionClass(B::class);
$ps = $rb->getProperty('s');
$ps->setAccessible(true);
$sv = $ps->getValue();
$ps->setValue(null, $sv + 5);
$ms = $rb->getMethod('mul');
$ms->setAccessible(true);
$z = $ms->invokeArgs(null, [3, 4]);
$rc = new ReflectionClass(C::class);
$ctor = $rc->getConstructor();
if ($ctor !== null) {
    $ctor->getNumberOfParameters();
}
?>
--EXPECT--
