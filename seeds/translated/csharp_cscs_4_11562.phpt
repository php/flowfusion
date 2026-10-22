--TEST--
Translated seed (inferredbugs-csharp: cscs/4)
--FILE--
<?php
$v = [];
$v["x"] = 1;
$v["x"] += "2";
$v["y"] = ["a" => 1];
$v["y"]["b"] = [];
$v["y"]["b"][0] = "3";
$v["y"]["b"][1] = 4;
$v["z"] = $v["x"] . $v["y"]["b"][0];
$w = $v;
$w["y"]["b"][1]++;
$w["y"]["b"][2] = $w["y"]["b"][1] ?? null;
$w["x"]--;
$w["x"] += 5;
$w["q"] = [];
$w["q"]["k"] = "7";
$w["q"]["k"] .= $w["x"];
$w["r"] = isset($w["q"]["missing"]) ? $w["q"]["missing"] : "fallback";
echo json_encode([$v, $w], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
