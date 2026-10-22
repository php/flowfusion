--TEST--
Translated seed (inferredbugs-csharp: cscs/7)
--FILE--
<?php
$v = [];
$v["a"] = [];
$v["a"][0] = 1;
$v["a"][2] = 3;
$v["a"]["x"] = 7;
$v["b"] = "12";
$v["b"] .= "3";
$v["c"] = 5;
$v["c"] += 2;
$v["c"] %= 4;
$v["d"] = [];
$v["d"][1] = [];
$v["d"][1][0] = "z";
$v["d"][1][2] = "y";
$k = array_keys($v);
sort($k);
$s = count($v["a"]);
$t = strlen($v["b"]);
$n = (int)$v["c"];
$u = isset($v["d"][1][2]) ? $v["d"][1][2] : null;
if ($s !== 3 || $t !== 3 || $n !== 3 || $u !== "y") {
    exit(1);
}
?>
--EXPECT--
