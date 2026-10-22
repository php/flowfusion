--TEST--
Translated seed (inferredbugs-java: commons-csv/132)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
           . " a , b , 1 2 \n"
           . "\"foo baar\", b,\n"
           . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCsv($input) {
    $rows = explode("\n", trim($input));
    $result = [];
    foreach ($rows as $row) {
        $result[] = str_getcsv($row);
    }
    return $result;
}

$records = parseCsv($csvInput);

foreach ($result as $key => $expected) {
    if (count($records[$key]) !== count($expected)) {
        exit(1);
    }
    foreach ($expected as $index => $value) {
        if ($records[$key][$index] !== $value) {
            exit(1);
        }
    }
}
--EXPECT--
