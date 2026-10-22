--TEST--
Translated seed (inferredbugs-java: commons-csv/77)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$expectedResults = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCsv($input) {
    return array_map('str_getcsv', explode("\n", trim($input)));
}

$records = parseCsv($csvInput);

foreach ($expectedResults as $index => $expected) {
    $record = $records[$index] ?? null;
    if ($record !== $expected) {
        echo "Mismatch at index $index: expected " . json_encode($expected) . " but got " . json_encode($record) . "\n";
    }
}

echo "Finished parsing CSV.";
--EXPECT--
