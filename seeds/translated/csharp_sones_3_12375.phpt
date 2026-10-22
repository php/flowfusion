--TEST--
Translated seed (inferredbugs-csharp: sones/3)
--FILE--
<?php
$items = [PHP_INT_MAX - 1, PHP_INT_MAX, -1, 0, 1];
$map = [];
foreach ($items as $i) {
    $k = (string)$i;
    $map[$k] = ($map[$k] ?? 0) + 1;
}
$copy = $map;
$map = [];
foreach (["9223372036854775806", "9223372036854775807", "-1", "0", "1"] as $k) {
    $map[$k] = $copy[$k] ?? 0;
}
$nested = [];
foreach ($map as $k => $v) {
    $nested[$k] = ["count" => $v, "self" => $k];
}
foreach ($nested as $k => $v) {
    if ($v["count"] > 0) {
        $nested[$k]["count"] += strlen($v["self"]) & 1;
    }
}
echo "";
?>
--EXPECT--
