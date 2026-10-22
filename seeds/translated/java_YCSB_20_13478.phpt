--TEST--
Translated seed (inferredbugs-java: YCSB/20)
--FILE--
<?php
$key = "testKey";
$values = [
    "field1" => "value1",
    "field2" => "value2"
];

$bufferedUpdates = false;

$mutateRow = function($key, $values) {
    echo "Setting up put for key: $key\n";
    foreach ($values as $field => $value) {
        echo "Setting $field to $value\n";
    }
};

if ($bufferedUpdates) {
    // Simulate async operations, just call the function directly for now
    $mutateRow($key, $values);
} else {
    $mutateRow($key, $values);
}

$deleteKey = function($key) {
    echo "Doing delete for key: $key\n";
};

$deleteKey($key);
--EXPECT--
