--TEST--
Translated seed (inferredbugs-csharp: Shielded/8)
--FILE--
<?php
$dict = [];
$versions = [];
$copies = [];
$readStamp = 0;

$dict["a"] = ["version" => 1, "value" => 1, "empty" => false, "older" => null];
$dict["b"] = ["version" => 1, "value" => 2, "empty" => false, "older" => null];

$local = ["items" => [], "hasChanges" => false, "locked" => false];

foreach (["a", "c"] as $key) {
    if (!array_key_exists($key, $local["items"])) {
        $local["items"][$key] = null;
    }
    $v = $dict[$key] ?? null;
    while ($v !== null && $v["version"] > $readStamp) {
        $v = $v["older"];
    }
    if ($v !== null && !$v["empty"]) {
        $local["items"][$key] = $v;
    }
}

$local["items"]["a"] = ["version" => 0, "value" => 10, "empty" => false, "older" => $dict["a"]];
$local["hasChanges"] = true;
$local["items"]["c"] = ["version" => 0, "value" => 3, "empty" => false, "older" => null];

if ($local["hasChanges"]) {
    $version = 2;
    $copyList = [];
    foreach ($local["items"] as $key => $item) {
        if ($item === null) continue;
        if (isset($dict[$key])) $copyList[] = $key;
        $item["version"] = $version;
        $item["older"] = $dict[$key] ?? null;
        $dict[$key] = $item;
    }
    $copies[] = [$version, $copyList];
}

$keys = array_unique(array_merge(array_keys($dict), array_keys($local["items"])));
$result = [];
foreach ($keys as $key) {
    $v = $dict[$key] ?? null;
    while ($v !== null && $v["version"] > $readStamp) {
        $v = $v["older"];
    }
    if ($v !== null && !$v["empty"]) {
        $result[$key] = $v["value"];
    }
}

foreach ($result as $k => $v) {
    echo $k . ":" . $v . "\n";
}
--EXPECT--
