--TEST--
Translated seed (inferredbugs-csharp: NRules/3)
--FILE--
<?php
class A { public int $x = 1; public function f(int $y): int { return $this->x + $y; } }

$o = new A();
$e = ["NodeType" => "Call", "Method.Name" => "f", "Method.DeclaringType" => A::class, "Object" => $o, "Arguments" => [2]];
$j = json_encode($e, JSON_THROW_ON_ERROR);
$d = json_decode($j, true, 512, JSON_THROW_ON_ERROR);

$cls = $d["Method.DeclaringType"];
$method = $d["Method.Name"];
$obj = $d["Object"];
$args = $d["Arguments"];

call_user_func_array([$obj, $method], $args);
echo "ok\n";
?>
--EXPECT--
