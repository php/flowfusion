--TEST--
Translated seed (inferredbugs-csharp: cscs/95)
--FILE--
<?php
$inf = INF;
$ninf = -INF;
$nan = acos(2);
$v = ["a" => null, "b" => $inf, "c" => $ninf, "d" => $nan, "e" => 0, "f" => "0", "g" => "1e309"];
foreach ($v as $k => $x) {
    $isNaN = is_float($x) && is_nan($x);
    $isFinite = is_int($x) || is_float($x) ? is_finite($x) : is_finite((float)$x);
    $type = gettype($x);
    $s = (string)$x;
    $i = (int)$s;
    $d = (float)$s;
    $b = filter_var($s, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $arr = [$x, $s, $i, $d, $b, $isNaN, $isFinite, $type];
    $arr[] = $arr;
    $arr["self"] = $arr;
    $arr["k"] = $k;
}
$u = [];
$u["x"] = &$u;
$u["y"] = &$u["x"];
$u["z"] = ["p" => 1, "q" => 2];
$u["z"]["q"] += 3;
$u["z"]["r"] = $u["z"]["p"] . $u["z"]["q"];
$u["z"]["s"] = array_key_exists("missing", $u["z"]);
$u["z"]["t"] = isset($u["z"]["missing"]);
$u["z"]["u"] = empty($u["z"]["missing"]);
$json = json_encode($u);
$decoded = json_decode($json, true);
$mix = ["01" => 1, 1 => 2, "1" => 3, true => 4, false => 5, null => 6];
$mix[] = $mix;
$mix["nested"] = ["x" => $mix, "y" => &$mix];
$mix["nested"]["y"]["new"] = "ok";
echo "";
?>
--EXPECT--
