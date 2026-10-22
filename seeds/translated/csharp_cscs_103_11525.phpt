--TEST--
Translated seed (inferredbugs-csharp: cscs/103)
--FILE--
<?php
$nan = NAN;
$inf = INF;
$ninf = -INF;

var_dump(is_nan($nan));
var_dump(is_finite($inf));
var_dump(is_finite($ninf));
var_dump(is_finite("123.5"));
var_dump(is_finite("1e309"));
var_dump(gettype("x"));
var_dump(gettype(123));
var_dump(gettype($nan));
var_dump(gettype($inf));
var_dump(array_key_exists("missing", ["a" => 1]));
var_dump(in_array(0, ["0", 1], true));
var_dump(in_array(0, ["0", 1], false));

$a = ["k" => ["x" => 1], 0 => [1, 2]];
$b = $a;
$b["k"]["x"] = 7;
$b[0][] = 3;
var_dump($a);
var_dump($b);

$s = "a,b\tc";
var_dump(explode("\t", $s));
var_dump(explode(",", $s));

$x = [];
$x["nested"]["deep"] = 1;
$x["nested"]["deep"] += 2;
$x["list"][] = 4;
$x["list"][] = 5;
var_dump($x);

$y = ["z" => 1];
$y["z"]++;
++$y["z"];
var_dump($y);

$z = ["arr" => [10, 20, 30]];
unset($z["arr"][1]);
$z["arr"][] = 40;
var_dump($z);

echo "ok\n";
?>
--EXPECT--
