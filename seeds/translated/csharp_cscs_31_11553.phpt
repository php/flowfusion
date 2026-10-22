--TEST--
Translated seed (inferredbugs-csharp: cscs/31)
--FILE--
<?php
$base = ['a' => 1, 'b' => 2];
$derived = $base;
$derived['c'] = 3;
$derived['a'] += 10;

$nested = [];
$nested['x'] = [];
$nested['x']['y'] = [];
$nested['x']['y'][] = 1;
$nested['x']['y'][] = 2;

$copy = $nested;
$copy['x']['y'][1] = 99;

$map = [];
$k = "01";
$map[$k] = "s";
$map[1] = "n";
$map["1"] = "o";

$arr = [1, 2, 3];
unset($arr[1]);
$arr[] = 4;
$arr[1] = 5;

$obj = new stdClass();
$obj->p = $derived;
$obj->p['d'] = 4;

$s = "123";
$n = (int)$s + (float)"4.5";
$b = (bool)"0";
$t = gettype($obj->p);

echo count($base) . "\n";
echo $derived['a'] . "\n";
echo $nested['x']['y'][0] . "\n";
echo $copy['x']['y'][1] . "\n";
echo $map['01'] . "\n";
echo $map[1] . "\n";
echo implode(',', $arr) . "\n";
echo $obj->p['d'] . "\n";
echo $n . "\n";
echo ($b ? "1" : "0") . "\n";
echo $t . "\n";
?>
--EXPECT--
