--TEST--
Translated seed (inferredbugs-csharp: Remora.Discord/1)
--FILE--
<?php
$store = [];
$key = "k";
$store[$key] = [1, ["x" => 1]];
if (!array_key_exists($key, $store)) {
    exit;
}
[$sem, $data] = $store[$key];
$store[$key][1]["x"]++;
$copy = $store[$key];
unset($store[$key]);
$store[$key] = $copy;
[$sem2, $data2] = $store[$key];
echo $data2["x"], "\n";
?>
--EXPECT--
