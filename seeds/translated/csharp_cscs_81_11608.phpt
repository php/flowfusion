--TEST--
Translated seed (inferredbugs-csharp: cscs/81)
--FILE--
<?php
$v = ["n" => 0, "s" => "0", "a" => [0, 1, 2], "m" => ["x" => ["y" => 1]]];

for ($i = 0; $i < 3; $i++) {
    $v["a"][$i] += $i;
    $v["m"]["x"]["y"] = $v["m"]["x"]["y"] + 1;
}

foreach (["1", 1, "01", "1.0", true, false, null, "", "0"] as $x) {
    $k = (string)$x;
    $v["h"][$k] = ($v["h"][$k] ?? 0) + 1;
}

$u = $v["a"];
$u[] = $v["m"]["x"]["y"];
array_splice($u, 1, 1);
array_unshift($u, -1);

$w = $u;
$w[0] = $w[0] . "";
$w["2"] = (int)$w["2"] + 1;

$z = [];
$z["0"] = "zero";
$z[0] = "intzero";
$z[""] = "empty";
$z[false] = "false";
$z[null] = "null";

echo json_encode([$v, $u, $w, $z], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
--EXPECT--
