--TEST--
Translated seed (inferredbugs-java: YCSB/28)
--FILE--
<?php

$tableName = "";
$clientSideBuffering = false;
$debug = false;
$columnFamily = "cf";
$recordCount = 100;

function read($table, $key, $fields) {
    global $tableName, $columnFamily;
    
    if ($tableName !== $table) {
        $tableName = $table;
    }

    $result = [];
    $data = ["key1" => "value1", "key2" => "value2"]; // Simulated data

    if (isset($data[$key])) {
        $result[$key] = $data[$key];
        if ($fields) {
            return array_filter($result, function($k) use ($fields) {
                return in_array($k, $fields);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $result;
    }
    return null;
}

function update($table, $key, $values) {
    global $tableName;

    if ($tableName !== $table) {
        $tableName = $table;
    }

    // Simulated update logic
    foreach ($values as $field => $value) {
        // Update operation
    }
}

function insert($table, $key, $values) {
    return update($table, $key, $values);
}

function delete($table, $key) {
    global $tableName;

    if ($tableName !== $table) {
        $tableName = $table;
    }
    
    // Simulated delete operation
}

$key = "key1";
$fields = ["key1", "key2"];

$readResult = read("testTable", $key, $fields);
$updateValues = ["key1" => "newValue1"];
insert("testTable", "key3", $updateValues);
delete("testTable", $key);
--EXPECT--
