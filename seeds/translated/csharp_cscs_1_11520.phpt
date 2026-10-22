--TEST--
Translated seed (inferredbugs-csharp: cscs/1)
--FILE--
<?php
$v = [1];
$v[] = 2;
$v[5] = 3;
$v["x"] = 4;
$v["x"] += 1;
$v[0] += 10;
$v[1] .= "a";
$v["y"] = [];
$v["y"][] = 7;
$v["y"][2] = 8;
$k = "x";
$exists = array_key_exists($k, $v);
$size = count($v);
$type = gettype($v);
$s = "123";
$n = is_numeric($s) ? $s + 0 : 0;
if ($exists && $size > 0 && $type === "array" && $n === 123) {
    $v["z"] = $v["x"] . ":" . $v[0];
}
echo "";
?>
--EXPECT--
