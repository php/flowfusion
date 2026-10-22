--TEST--
Translated seed (inferredbugs-csharp: pose/3)
--FILE--
<?php
class Box {
    public $value;
    public function __construct($v) { $this->value = $v; }
    public function getValue() { return $this->value; }
}

class Pair {
    public $x;
    public $y;
    public function __construct($x, $y) { $this->x = $x; $this->y = $y; }
}

$box = new Box(new Pair(1, 2));
$ref = new ReflectionObject($box);

$prop = $ref->getProperty('value');
$instance = $prop->getValue($box);

$method = new ReflectionMethod($box, 'getValue');
$signature = $method->getReturnType();
$params = $method->getParameters();

$ctor = new ReflectionMethod('Pair', '__construct');
$ctorParams = $ctor->getParameters();

$staticPropHolder = new class {
    public static $shared;
};
$staticPropHolder::$shared = new Box(new Pair(3, 4));
$staticRef = new ReflectionClass($staticPropHolder);
$staticProp = $staticRef->getProperty('shared');
$staticInstance = $staticProp->getValue();

$nested = $instance->x + $instance->y + $staticInstance->value->x + $staticInstance->value->y;
echo $nested, PHP_EOL;
?>
--EXPECT--
