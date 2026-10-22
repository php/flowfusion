--TEST--
Translated seed (inferredbugs-csharp: cscs/71)
--FILE--
<?php
function v($x) { return is_array($x) ? $x : (is_object($x) ? get_object_vars($x) : $x); }

$a = [];
$a[2][1] = "x";
$a["k"] = ["p" => 1];
$b = $a;
$b[2][1] = "y";
$b["k"]["p"] += 2;

$s = "12";
$i = (int)$s;
$f = (float)"3.14";
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

$x = 1;
$y = &$x;
$y++;
$z = $x;

$mix = [];
$mix[] = $a;
$mix[] = $b;
$mix[] = ["n" => null, "m" => [0, 1, 2]];
unset($mix[1]["k"]);

$copy = unserialize(serialize($mix));
$copy[2]["m"][1] = 9;

echo json_encode([
    "a" => $a,
    "b" => $b,
    "i" => $i,
    "f" => $f,
    "bool" => $bool,
    "x" => $x,
    "z" => $z,
    "copy" => $copy
], JSON_PRESERVE_ZERO_FRACTION), PHP_EOL;
?>
--EXPECT--
