--TEST--
Translated seed (inferredbugs-java: commons-csv/107)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$expected = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$results = [];

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '') continue;
    $results[] = str_getcsv($line);
}

foreach ($expected as $index => $expectedRow) {
    if (isset($results[$index])) {
        $resultRow = $results[$index];
        foreach ($expectedRow as $key => $value) {
            if (!isset($resultRow[$key]) || $resultRow[$key] !== $value) {
                exit(1);
            }
        }
    } else {
        exit(1);
    }
}

if (count($results) !== count($expected)) {
    exit(1);
}

exit(0);
--EXPECT--
