--TEST--
Translated seed (inferredbugs-csharp: cscs/43)
--FILE--
<?php
$values = [[1, 2], [3, 4]];
$ref =& $values[0];
$ref[1] = 99;
$values[] = [&$ref, $values[1]];
$copy = $values;
$copy[0][0] += 10;
$copy[2][0][1] -= 1;
$map = [];
$map["a"] = $values;
$map["b"] = $copy;
$map["a"][0][1] = 7;
$map["b"][2][0][0] = 8;
$nested = ["x" => ["y" => ["z" => 1]]];
$nested["x"]["y"]["z"]++;
$nested["x"]["y"]["w"] = $map;
$nested["x"]["y"]["w"]["a"][2][1] = 5;
$nested["x"]["y"]["w"]["b"][0][1] = 6;
echo count($values) . "\n";
echo $values[0][1] . "\n";
echo $copy[0][0] . "\n";
echo $map["a"][0][1] . "\n";
echo $nested["x"]["y"]["z"] . "\n";
echo $nested["x"]["y"]["w"]["b"][2][0][0] . "\n";
?>
--EXPECT--
