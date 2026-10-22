--TEST--
Translated seed (inferredbugs-csharp: MappingGenerator/1)
--FILE--
<?php
$s = [1, 2, 3];
$t = [];
foreach ($s as $x) {
    $t[] = $x;
}
$u = ["a" => 1, "b" => 2];
$v = [];
foreach ($u as $k => $x) {
    $v[$k] = $x;
}
$w = ["items" => [["n" => 1], ["n" => 2]], "name" => "x"];
$z = ["items" => [], "name" => ""];
foreach ($w["items"] as $item) {
    $z["items"][] = ["n" => $item["n"]];
}
$z["name"] = $w["name"];
echo count($t) + count($v) + count($z["items"]) . PHP_EOL;
?>
--EXPECT--
