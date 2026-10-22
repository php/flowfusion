--TEST--
Translated seed (inferredbugs-csharp: cscs/92)
--FILE--
<?php
$v = ["a" => 1, "b" => 2];
$k = "a";
$ref =& $v[$k];
$ref++;
$u = [];
$u["x"]["y"] = 3;
$u["x"]["z"] = $u["x"]["y"] + 1;
$s = "42";
$n = (int)$s;
$f = (float)"3.14";
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN);
$arr = [1, 2, 3];
array_splice($arr, 1, 1);
array_push($arr, 4);
$map = [];
$map["key"] = $arr;
$copy = $map;
$copy["key"][0] = 99;
$nested = ["p" => ["q" => ["r" => 7]]];
$tmp = $nested["p"]["q"]["r"] ?? null;
echo $v["a"], "\n";
echo $u["x"]["z"], "\n";
echo $n + (int)$f + (int)$b + count($arr) + $copy["key"][0] + $tmp, "\n";
?>
--EXPECT--
