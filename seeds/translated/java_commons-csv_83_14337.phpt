--TEST--
Translated seed (inferredbugs-java: commons-csv/83)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n"
            . " a , b , 1 2 \n"
            . "\"foo baar\", b,\n"
            . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCSV($input) {
    $rows = array_map('str_getcsv', explode("\n", $input));
    return array_map(function($row) {
        return array_map('trim', $row);
    }, $rows);
}

$records = parseCSV($CSVINPUT);
foreach ($RESULT as $index => $expected) {
    if ($records[$index] !== $expected) {
        exit("Test failed at row $index\n");
    }
}

exit("All tests passed\n");
--EXPECT--
