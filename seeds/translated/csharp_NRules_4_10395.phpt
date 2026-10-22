--TEST--
Translated seed (inferredbugs-csharp: NRules/4)
--FILE--
<?php
class A {
    public int $x = 1;
    public function add(int $y): int { return $this->x + $y; }
}

$a = new A();
$r = new ReflectionClass($a);
$p = $r->getProperty('x');
$m = $r->getMethod('add');

$values = [
    $p->getName(),
    $p->getType()->getName(),
    $m->getName(),
    $m->getNumberOfParameters(),
    $m->getReturnType()->getName(),
];

$json = json_encode($values);
$decoded = json_decode($json, true);

$obj = new A();
$prop = $decoded[0];
$type = $decoded[1];
$method = $decoded[2];
$args = [(int)$decoded[3]];

if ($type === 'int') {
    $obj->$prop = 7;
}

$result = $obj->$method(...$args);
echo json_encode([$result, $obj->x]), PHP_EOL;
?>
--EXPECT--
