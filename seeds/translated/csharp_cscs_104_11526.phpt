--TEST--
Translated seed (inferredbugs-csharp: cscs/104)
--FILE--
<?php
$nan = acos(2);
$inf = INF;
$ninf = -INF;

var_dump(is_nan($nan));
var_dump(is_finite($inf));
var_dump(is_finite($ninf));
var_dump(gettype("123"));
var_dump(gettype(123));
var_dump(gettype($nan));
var_dump(gettype($inf));
var_dump((int)"42");
var_dump((float)"3.14");
var_dump((bool)"0");
var_dump((bool)"1");
var_dump((bool)"");
var_dump(array_key_exists("x", ["x" => null]));
var_dump(in_array(null, [0, false, null], true));
var_dump(count(array_slice([1, 2, 3, 4], 1, 2)));
var_dump(substr("abcdef", 2, 3));
var_dump(strpos("abcdef", "de") !== false);
var_dump(json_decode('{"a":1,"b":[2,3]}', true)["b"][1]);
var_dump(array_search("b", ["a", "b", "c"], true));
var_dump((new ArrayObject(["k" => "v"]))->offsetExists("k"));
echo "done\n";
?>
--EXPECT--
