--TEST--
Translated seed (inferredbugs-csharp: storage/1)
--FILE--
<?php
$store = [];
$id = "a/b";
$data = "hello";
$store[$id] = ["data" => $data, "lastMod" => time(), "md5" => md5($data)];
$append = " world";
if (array_key_exists($id, $store)) {
    $store[$id]["data"] .= $append;
    $store[$id]["lastMod"] = time();
    $store[$id]["md5"] = md5($store[$id]["data"]);
}
$ids = [$id, "missing"];
$exists = [];
$meta = [];
foreach ($ids as $x) {
    $exists[] = array_key_exists($x, $store);
    $meta[] = array_key_exists($x, $store) ? ["length" => strlen($store[$x]["data"]), "md5" => $store[$x]["md5"], "lastMod" => $store[$x]["lastMod"]] : null;
}
unset($store[$id]);
$read = $store[$id]["data"] ?? null;
echo "";
?>
--EXPECT--
