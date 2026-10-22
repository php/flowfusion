--TEST--
Translated seed (inferredbugs-csharp: cscs/5)
--FILE--
<?php
$v = [];
$v["x"] = 1;
$v["s"] = "1";
$v["a"] = [0 => 10, 2 => 20];
$v["b"] = [];
$v["b"]["k"] = "v";
$v["n"] = 3.5;

$v["x"] += 2;
$v["x"] -= 1;
$v["x"] *= 4;
$v["x"] /= 2;
$v["x"] %= 3;
$v["x"] &= 7;
$v["x"] |= 8;
$v["x"] ^= 1;

$v["s"] .= $v["x"];
$v["a"][1] = $v["x"];
$v["a"][5] = $v["s"];
$v["b"]["k"] .= $v["a"][2];

$types = [
    gettype($v["x"]),
    gettype($v["s"]),
    gettype($v["a"]),
    gettype($v["b"]),
    gettype($v["n"]),
    isset($v["a"][5]),
    array_key_exists("k", $v["b"]),
    count($v["a"]),
    strlen($v["s"])
];

foreach ($types as $t) {
    if (is_bool($t)) {
        $t ? null : null;
    }
}
?>
--EXPECT--
