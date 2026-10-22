--TEST--
Translated seed (inferredbugs-csharp: R2API/3)
--FILE--
<?php
class A {
    private int $x = 1;
    protected static int $s = 2;
    private function add(int $a, int $b): int { return $a + $b + $this->x; }
}
class B extends A {
    private string $y = "z";
    private static function make(int $n): self { $o = new self; $o->y = str_repeat($o->y, $n); return $o; }
}
$o = new B;
$r = new ReflectionClass($o);
$p = $r->getProperty('y');
$p->setAccessible(true);
$v = $p->getValue($o);
$p->setValue($o, $v . $v);
$m = $r->getMethod('add');
$m->setAccessible(true);
$m->invoke($o, 1, 2);
$sr = new ReflectionClass('A');
$sp = $sr->getProperty('s');
$sp->setAccessible(true);
$sp->setValue(null, $sp->getValue() + 1);
$sm = (new ReflectionClass('B'))->getMethod('make');
$sm->setAccessible(true);
$sm->invoke(null, 3);
$rc = new ReflectionClass('B');
$rc->getProperty('y')->setAccessible(true);
$rc->getProperty('y')->getValue($o);
$rc->getMethod('make')->setAccessible(true);
$rc->getMethod('make')->invoke(null, 1);
echo "";
?>
--EXPECT--
