--TEST--
Translated seed (inferredbugs-csharp: dotNext/1)
--FILE--
<?php
class Box { public int $x = 0; public function inc(int $d): int { return $this->x += $d; } public function get(): int { return $this->x; } public function set(int $v): void { $this->x = $v; } }
class ChildBox extends Box {}
class OtherBox { public int $x = 7; public function inc(int $d): int { return $this->x += $d; } }

$rm = new ReflectionMethod(Box::class, 'inc');
$rc = new ReflectionClass(ChildBox::class);
$obj = $rc->newInstance();
$rm->invoke($obj, 3);
$rm->invokeArgs($obj, [4]);

$getter = new ReflectionMethod(Box::class, 'get');
$setter = new ReflectionMethod(Box::class, 'set');
$getter->invoke($obj);
$setter->invoke($obj, 10);

$rm2 = new ReflectionMethod(OtherBox::class, 'inc');
$other = new OtherBox();
$rm2->invoke($other, 5);

$methods = [new ReflectionMethod(Box::class, 'inc'), new ReflectionMethod(Box::class, 'get'), new ReflectionMethod(Box::class, 'set')];
foreach ($methods as $m) {
    $m->getName();
    $m->getNumberOfParameters();
    $m->isPublic();
    $m->isStatic();
    $m->getDeclaringClass()->getName();
    $m->getReturnType();
}

$anon = new class {
    private int $v = 1;
    private function bump(int $n): int { return $this->v += $n; }
};
$ra = new ReflectionClass($anon);
$pm = $ra->getMethod('bump');
$pm->setAccessible(true);
$pm->invoke($anon, 2);
$pm->invokeArgs($anon, [3]);
?>
--EXPECT--
