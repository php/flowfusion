--TEST--
Translated seed (inferredbugs-csharp: pose/4)
--FILE--
<?php
class Box {
    public int $x = 1;
    public function getX(): int { return $this->x; }
}

$box = new Box();
$ref = new ReflectionMethod($box, 'getX');
$instance = $ref->getClosure($box);
$instance();

$ctor = new ReflectionClass(Box::class);
$ctor->newInstance();

$prop = new ReflectionProperty(Box::class, 'x');
$prop->getValue($box);

$method = new ReflectionMethod(Box::class, 'getX');
$method->invoke($box);

echo "ok\n";
?>
--EXPECT--
