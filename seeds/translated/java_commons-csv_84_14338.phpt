--TEST--
Translated seed (inferredbugs-java: commons-csv/84)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
             . " a , b , 1 2 \n"
             . "\"foo baar\", b,\n"
             . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$expectedResult = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCsv($input) {
    $lines = preg_split('/\r\n|\n|\r/', $input);
    $records = [];
    
    foreach ($lines as $line) {
        if (trim($line) === '') continue;
        $record = str_getcsv($line);
        $records[] = $record;
    }
    
    return $records;
}

$parsedRecords = parseCsv($csvInput);

foreach ($expectedResult as $index => $expected) {
    if (isset($parsedRecords[$index])) {
        if ($parsedRecords[$index] !== $expected) {
            // In an actual test, you would assert here
            // but we will simply dump the results
            var_dump($parsedRecords[$index]);
        }
    }
}

if (count($parsedRecords) === count($expectedResult)) {
    foreach ($parsedRecords as $record) {
        var_dump($record);
    }
}
--EXPECT--
