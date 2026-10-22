--TEST--
Translated seed (inferredbugs-java: commons-csv/76)
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
$parsedRecords = [];
foreach ($lines as $line) {
    $parsedRecords[] = str_getcsv($line);
}

foreach ($result as $index => $expected) {
    if (isset($parsedRecords[$index])) {
        print_r($parsedRecords[$index]);
    }
}

if (count($parsedRecords) < count($result)) {
    echo "null\n";
}
--EXPECT--
