--TEST--
Translated seed (inferredbugs-csharp: R2API/1)
--FILE--
<?php
class A {
    private int $x = 1;
    private static int $s = 2;
    private function inc(int $v): int { return $this->x + $v; }
    private static function add(int $a, int $b): int { return self::$s + $a + $b; }
}
class B extends A {
    private int $y = 3;
    private static int $t = 4;
    private function mul(int $v): int { return $v * 2; }
}
$o = new B();
$r = new ReflectionClass($o);
$p = $r->getProperty('x');
$p->setAccessible(true);
$v = $p->getValue($o);
$p->setValue($o, $v + 1);
$sp = $r->getProperty('s');
$sp->setAccessible(true);
$sv = $sp->getValue();
$sp->setValue($sv + 1);
$m = $r->getMethod('inc');
$m->setAccessible(true);
$a = $m->invoke($o, 5);
$sm = $r->getMethod('add');
$sm->setAccessible(true);
$b = $sm->invoke(null, 6, 7);
$rp = new ReflectionProperty($r->getParentClass()->getName(), 'x');
$rp->setAccessible(true);
$rp->getValue($o);
$rm = new ReflectionMethod($r->getParentClass()->getName(), 'inc');
$rm->setAccessible(true);
$rm->invoke($o, 8);
$c = new ReflectionClass('DateTime');
$dt = $c->newInstance('2000-01-01');
$fmt = $c->getMethod('format');
$fmt->invoke($dt, 'Y-m-d');
echo "";
?>
--EXPECT--
