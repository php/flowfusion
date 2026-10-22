--TEST--
Translated seed (inferredbugs-csharp: OpenSAGE/3)
--FILE--
<?php
class V {
    public array $vars = [];
    function __construct(public $v = null) {}
    function toObject() { return $this; }
    function getMember($k) { return $this->vars[(string)$k] ?? null; }
    function __toString(): string { return is_string($this->v) ? $this->v : (string)$this->v; }
}

$stack = [];
$scope = new V();
$scope->vars["x"] = new V("ok");
$scope->vars["n"] = new V(123);
$scope->vars["nested"] = new V();
$scope->vars["nested"]->vars["y"] = new V("world");

$stack[] = new V("x");
$stack[] = new V("root");
$member = array_pop($stack);
$obj = array_pop($stack);
$scope->vars[(string)$member] = $obj;
$stack[] = $scope->getMember("root");

$stack[] = new V("nested");
$stack[] = new V("y");
$obj = array_pop($stack);
$member = array_pop($stack);
$obj->toObject()->vars[(string)$member] = new V("php");
$stack[] = $obj->getMember("y");

$stack[] = new V("x");
$stack[] = new V("n");
$stack[] = new V("z");
$obj = array_pop($stack);
$member = array_pop($stack);
$value = array_pop($stack);
$obj->toObject()->vars[(string)$member] = $value;
$stack[] = $scope->vars["n"];

$stack[] = new V("nested");
$stack[] = new V("y");
$obj = array_pop($stack);
$member = array_pop($stack);
$stack[] = $obj->toObject()->getMember((string)$member);

echo "done\n";
?>
--EXPECT--
