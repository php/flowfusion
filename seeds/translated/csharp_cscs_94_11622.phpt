--TEST--
Translated seed (inferredbugs-csharp: cscs/94)
--FILE--
<?php
error_reporting(E_ALL);

$a = [1, 2, 3];
$b = &$a;
$b[1] = 99;
$c = ["x" => ["y" => 1]];
$d = $c;
$d["x"]["y"] = 7;

$e = [];
$e["k"] = null;
$e["k"] ??= 5;

$f = ["n" => NAN, "i" => INF, "s" => "123", "t" => "abc"];
var_dump(is_nan($f["n"]), is_finite($f["i"]), is_nan($f["s"] + 0), is_finite((float)$f["s"]));
var_dump(gettype($f["s"] + 0), gettype((int)$f["s"]), gettype((bool)$f["t"]));

$g = ["a" => 1];
$g["b"] = &$g["a"];
$g["b"] = 4;

$h = [[1, 2], [3, 4]];
foreach ($h as &$row) {
    $row[] = count($row);
}
unset($row);

$i = ["p" => ["q" => ["r" => 1]]];
$j = &$i["p"]["q"];
$j["r"] = 8;

echo json_encode([$a, $c, $e, $f, $g, $h, $i], JSON_PRESERVE_ZERO_FRACTION), PHP_EOL;
?>
--EXPECT--
