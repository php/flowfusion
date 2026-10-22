--TEST--
Translated seed (inferredbugs-csharp: pose/1)
--FILE--
<?php
class Box {
    public int $v = 7;
    public function getV(): int { return $this->v; }
}
class Holder {
    public static int $s = 11;
}

$box = new Box();
$prop = (new ReflectionClass(Box::class))->getProperty('v');
$method = (new ReflectionClass(Box::class))->getMethod('getV');
$staticProp = (new ReflectionClass(Holder::class))->getProperty('s');

$instance = null;
if (!$prop->isStatic()) {
    $instance = $box;
}
$prop->setValue($instance, 13);
$instance = null;
if (!$method->isStatic()) {
    $instance = $box;
}
$method->invoke($instance);

$staticProp->setValue(null, 17);

$original = $method;
$replacement = new ReflectionMethod(Box::class, 'getV');

$valid = [];
if (!$original->isStatic() && !$original->isConstructor()) {
    $valid[] = $original->getDeclaringClass()->getName();
}
foreach ($original->getParameters() as $p) {
    $valid[] = (string)$p->getType();
}
$validSignature = ($original->isConstructor() ? 'void' : (string)$original->getReturnType()) . ' (' . implode(', ', $valid) . ')';

$shim = [];
foreach ($replacement->getParameters() as $p) {
    $shim[] = (string)$p->getType();
}
$shimSignature = (string)$replacement->getReturnType() . ' (' . implode(', ', $shim) . ')';

$ok = ($validSignature === $shimSignature);
$box->v = $ok ? $box->v : $box->v;
?>
--EXPECT--
