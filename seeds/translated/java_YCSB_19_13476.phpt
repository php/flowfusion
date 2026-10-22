--TEST--
Translated seed (inferredbugs-java: YCSB/19)
--FILE--
<?php

$tableName = "";
$debug = false;
$currentTable = null;

function init() {
    global $tableName, $debug;
    $tableName = "example_table"; // simulate table name initialization
    $debug = true; // simulate debug mode
}

function read($key) {
    global $currentTable, $tableName, $debug;
    
    if ($currentTable !== $tableName) {
        $currentTable = $tableName; // simulate table access
    }

    if ($debug) {
        echo "Reading from table: $currentTable for key: $key\n";
    }

    // simulate reading from a database
    $data = [$key => str_repeat('value', 1000)]; // simulate a large value
    return $data;
}

function simulateBug() {
    $key = "example_key";
    $result = read($key);
    
    // simulate a potential bug pattern with large data
    foreach ($result as $field => $value) {
        echo "Field: $field, Value Length: " . strlen($value) . "\n";
    }
}

init();
simulateBug();

?>
--EXPECT--
