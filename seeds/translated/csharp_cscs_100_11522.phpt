--TEST--
Translated seed (inferredbugs-csharp: cscs/100)
--FILE--
<?php
$nan = NAN;
$inf = INF;
$ninf = -INF;

var_dump(is_nan($nan));
var_dump(is_finite($inf));
var_dump(is_finite($ninf));
var_dump(gettype("123"));
var_dump(gettype(123));
var_dump(gettype("x"));
var_dump(in_array("2", [2], true));
var_dump(in_array("2", [2], false));

$a = ["x" => 1];
$b = $a;
$b["x"] += 1;
var_dump($a["x"], $b["x"]);

$arr = [1, 2, 3];
$ref =& $arr[1];
$ref++;
var_dump($arr);

$s = "1";
$s += 2;
var_dump($s);

$x = 0;
for ($i = 0; $i < 3; $i++) {
    if ($i === 1) continue;
    if ($i === 2) break;
    $x += $i;
}
var_dump($x);

try {
    throw new Exception((string)123);
} catch (Exception $e) {
    var_dump($e->getMessage());
}

echo "done\n";
?>
--EXPECT--
