--TEST--
Translated seed (inferredbugs-csharp: cscs/33)
--FILE--
<?php
$xs = [1, 2, 3];
$ref =& $xs;
$ref[1] += 10;
$map = [];
$map["a"]["b"] = 1;
$map["a"]["c"] = 2;
$copy = $map;
$copy["a"]["b"] = 99;
$vals = ["1", 1, 1.0, true, false, null, "01", "1e0", " 1 ", "foo"];
$out = [];
foreach ($vals as $v) {
    $out[] = [gettype($v), (string)$v, (int)$v, (bool)$v];
}
$nested = [[["x" => 1]], [["x" => 2]]];
$nested[0][0]["x"]++;
$nested[1][0]["x"] += $ref[1];
echo json_encode([$xs, $map, $copy, $out, $nested], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
