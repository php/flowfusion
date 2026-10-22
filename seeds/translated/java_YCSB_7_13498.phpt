--TEST--
Translated seed (inferredbugs-java: YCSB/7)
--FILE--
<?php

$table = "usertable";
$key = "test_key";
$values = [
    "field1" => "value1",
    "field2" => "value2"
];

$cache = [];
$isClient = true;

function getRegion($table) {
    global $cache;
    if (!isset($cache[$table])) {
        $cache[$table] = [];
    }
    return $cache[$table];
}

function update($table, $key, $values) {
    $region = getRegion($table);
    $region[$key] = $values;
}

function read($table, $key, $fields = null) {
    $region = getRegion($table);
    $val = $region[$key] ?? null;
    if ($val !== null) {
        if ($fields === null) {
            return $val;
        } else {
            return array_intersect_key($val, array_flip($fields));
        }
    }
    return null;
}

update($table, $key, $values);
$result = read($table, $key, ["field1"]);
var_dump($result);
--EXPECT--
