--TEST--
Translated seed (inferredbugs-java: YCSB/30)
--FILE--
<?php
$columnFamily = "testFamily";
$debug = true;

function setTable($table) {
    global $lastTable, $lastTableBytes;
    if ($lastTable !== $table) {
        $lastTable = $table;
        $lastTableBytes = $table;
    }
}

$table = "testTable";
$key = "testKey";
$values = ["field1" => "value1", "field2" => "value2"];

setTable($table);

if ($debug) {
    echo "Setting up put for key: " . $key . "\n";
}

$clientSideBuffering = false;
$bulkMutation = [];

foreach ($values as $column => $value) {
    if ($clientSideBuffering) {
        $bulkMutation[] = [
            'family' => $columnFamily,
            'column' => $column,
            'value' => $value
        ];
    } else {
        // Directly mutating the row without buffering
        echo "Updating $columnFamily.$column to $value for $key\n";
    }
}

if ($clientSideBuffering) {
    foreach ($bulkMutation as $mutation) {
        echo "Buffered mutation: {$mutation['family']}.{$mutation['column']} = {$mutation['value']}\n";
    }
}
--EXPECT--
