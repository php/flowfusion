--TEST--
Translated seed (inferredbugs-csharp: cscs/105)
--FILE--
<?php
$nan = acos(8);
var_dump(is_nan($nan));
var_dump(is_finite(1.0 / 3.0));
var_dump(is_finite(INF));
var_dump(gettype("123"));
var_dump(gettype(123));
var_dump(gettype(null));
$a = [1, 2, 3];
$b = $a;
$b[1] = 99;
var_dump($a, $b);
$s = "x";
$s .= 1;
var_dump($s);
$x = [];
$x["k"] = [];
$x["k"]["n"] = 7;
var_dump($x);
$y = [1, [2, 3], ["a" => 4]];
var_dump(count($y), count($y[1]), array_key_exists("a", $y[2]));
$z = ["0" => "zero", 0 => "intzero"];
var_dump($z);
$u = null;
var_dump(isset($u), is_null($u));
$bytes = str_split("abc");
var_dump($bytes);
echo "done\n";
?>
--EXPECT--
