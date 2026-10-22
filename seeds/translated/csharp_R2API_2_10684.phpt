--TEST--
Translated seed (inferredbugs-csharp: R2API/2)
--FILE--
<?php
class A { private int $x = 1; public static int $s = 2; private function inc(int $v): int { return $this->x += $v; } }
class B extends A { private string $y = "3"; public function getY(): string { return $this->y; } }
class C { public int $v = 4; public function __construct(int $a, string $b) { $this->v = $a + strlen($b); } }

$o = new B();
$r = new ReflectionClass($o);
$p = $r->getParentClass()->getProperty('x');
$p->setAccessible(true);
$v = $p->getValue($o);
$p->setValue($o, $v + 5);

$sp = $r->getParentClass()->getProperty('s');
$sp->setAccessible(true);
$sv = $sp->getValue();
$sp->setValue(null, $sv + 7);

$mp = $r->getMethod('inc');
$mp->setAccessible(true);
$mp->invoke($o, 9);

$yp = $r->getProperty('y');
$yp->setAccessible(true);
$yp->setValue($o, (string)((int)$yp->getValue($o) + 11));

$cr = new ReflectionClass(C::class);
$inst = $cr->newInstanceArgs([6, "zz"]);
$cp = $cr->getProperty('v');
$cp->setAccessible(true);
$cp->setValue($inst, $cp->getValue($inst) + 13);

$anon = new class(8) { private int $a; public function __construct(int $a){ $this->a = $a; } private function f(): int { return $this->a * 2; } };
$ar = new ReflectionClass($anon);
$ap = $ar->getProperty('a');
$ap->setAccessible(true);
$ap->setValue($anon, $ap->getValue($anon) + 1);
$am = $ar->getMethod('f');
$am->setAccessible(true);
$am->invoke($anon);
?>
--EXPECT--
