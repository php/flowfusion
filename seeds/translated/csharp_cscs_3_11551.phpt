--TEST--
Translated seed (inferredbugs-csharp: cscs/3)
--FILE--
<?php
$v = [];
$v["x"] = 1;
$v["x"] += 2;
$v["s"] = "a";
$v["s"] .= "b";
$v["a"] = [];
$v["a"][2] = 7;
$v["a"][0] = 3;
$v["a"][1] = [];
$v["a"][1]["k"] = 9;
$v["a"][1]["k"]++;
$v["a"][1]["k"]--;
$t1 = gettype($v["a"]);
$t2 = gettype($v["a"][1]);
$s1 = count($v["a"]);
$s2 = strlen($v["s"]);
$exists = array_key_exists(1, $v["a"]) && array_key_exists("k", $v["a"][1]);
if (!$exists || $t1 !== "array" || $t2 !== "array" || $s1 !== 3 || $s2 !== 2) {
    exit(1);
}
echo $v["x"], "\n", $v["s"], "\n", $v["a"][2], "\n", $v["a"][1]["k"], "\n";
?>
--EXPECT--
