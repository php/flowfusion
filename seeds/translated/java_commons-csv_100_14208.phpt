--TEST--
Translated seed (inferredbugs-java: commons-csv/100)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$records = [];

foreach ($lines as $line) {
    $records[] = str_getcsv($line);
}

foreach ($result as $key => $expected) {
    if (isset($records[$key])) {
        $record = $records[$key];
        if ($record !== $expected) {
            // Output the unexpected record for inspection
            echo "Unexpected record: " . implode(", ", $record) . "\n";
        }
    }
} 

if (count($records) < count($result)) {
    echo "Not enough records: expected " . count($result) . " but got " . count($records) . "\n";
} else {
    echo "Records processed successfully.\n";
}
--EXPECT--
