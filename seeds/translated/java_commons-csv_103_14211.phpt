--TEST--
Translated seed (inferredbugs-java: commons-csv/103)
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

function parseCsv($input) {
    return array_map('str_getcsv', explode("\n", trim($input)));
}

$parsedRecords = parseCsv($CSVINPUT);
foreach ($RESULT as $index => $expected) {
    if ($parsedRecords[$index] !== $expected) {
        exit(1);
    }
}

exit(0);
--EXPECT--
