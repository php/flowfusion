--TEST--
Translated seed (inferredbugs-csharp: R2API/5)
--FILE--
<?php
class B {
    public $x = 1;
    public static $s = 2;
    private $p = 3;
    private static $ps = 4;
    public function m($a, $b) { return $a + $b + $this->x; }
    private function pm() { return $this->p + self::$ps; }
}
class C extends B {
    public $y = 5;
    private $z = 6;
}
$o = new C();
$r = new ReflectionClass($o);
$f = $r->getProperty('p');
$f->setAccessible(true);
$v = $f->getValue($o);
$f->setValue($o, $v + 1);
$sf = $r->getProperty('ps');
$sf->setAccessible(true);
$sv = $sf->getValue();
$sf->setValue(null, $sv + 1);
$m = $r->getMethod('pm');
$m->setAccessible(true);
$m->invoke($o);
$cm = $r->getMethod('m');
$cm->invokeArgs($o, [1, 2]);
$rc = new ReflectionClass('C');
$ctor = $rc->getConstructor();
$inst = $rc->newInstanceWithoutConstructor();
$props = ['x', 'y', 'z'];
foreach ($props as $name) {
    $p = $rc->getProperty($name);
    $p->setAccessible(true);
    $p->getValue($inst);
}
$nested = class_exists('ReflectionClass', false);
if ($nested) {
    $arr = [new C(), new C()];
    foreach ($arr as $obj) {
        $rr = new ReflectionClass($obj);
        foreach (['x', 'y'] as $n) {
            $pp = $rr->getProperty($n);
            $pp->setAccessible(true);
            $pp->setValue($obj, $pp->getValue($obj) + 1);
        }
    }
}
?>
--EXPECT--
