--TEST--
Translated seed (inferredbugs-csharp: cscs/57)
--FILE--
<?php
$values = [];
for ($i = 0; $i < 4; $i++) {
    $values[] = $i;
}
$refs = [];
foreach ($values as &$v) {
    $refs[] = &$v;
}
unset($v);
$removed = array_splice($values, 1, 1);
$values[] = 99;
$values[0] += 1;
$values[2] .= "";
$map = [];
$map["a"] = $values;
$map["b"] = $refs;
$nested = [["x" => 1], ["x" => 2], ["x" => 3]];
$col = array_column($nested, "x");
$keys = array_keys($map);
$exists = array_key_exists("a", $map) && in_array(99, $values, true);
$joined = implode(",", $col);
echo $joined . "\n";
echo count($removed) . ":" . count($values) . ":" . count($refs) . "\n";
echo ($exists ? "1" : "0") . ":" . $keys[0] . ":" . $keys[1] . "\n";
?>
--EXPECT--
