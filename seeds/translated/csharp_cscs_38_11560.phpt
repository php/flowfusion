--TEST--
Translated seed (inferredbugs-csharp: cscs/38)
--FILE--
<?php
$lines = ["", "0", "1", "2", "10", "01", " 3 ", "3.14", "true", "false", "abc", "1e309", "-0", "000123", "9223372036854775807", "9223372036854775808"];
$values = [];
foreach ($lines as $s) {
    $values[] = ["s" => $s, "i" => (int)$s, "f" => (float)$s, "b" => filter_var($s, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)];
}
$map = [];
foreach ($values as $v) {
    $k = trim($v["s"]);
    $map[$k][] = $v;
}
$nested = [];
foreach ($map as $k => $group) {
    $nested[$k] = ["count" => count($group), "first" => $group[0], "last" => $group[count($group) - 1]];
}
$json = json_encode($nested);
$decoded = json_decode($json, true);
$copy = $decoded;
foreach ($copy as $k => $v) {
    if (isset($v["first"]["s"]) && $v["first"]["s"] === "") {
        $copy[$k]["first"]["s"] = "0";
    }
}
$merged = array_merge_recursive($decoded, $copy);
$flat = [];
foreach ($merged as $k => $v) {
    $flat[$k] = is_array($v) ? count($v, COUNT_RECURSIVE) : 1;
}
echo count($flat), "\n";
?>
--EXPECT--
