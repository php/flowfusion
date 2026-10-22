--TEST--
Translated seed (inferredbugs-java: commons-csv/48)
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
    $rows = explode("\n", $input);
    $result = [];
    foreach ($rows as $row) {
        $result[] = str_getcsv($row);
    }
    return $result;
}

$records = parseCSV($CSVINPUT);

foreach ($RESULT as $index => $expected) {
    if ($records[$index] !== $expected) {
        exit(1);
    }
}
exit(0);
--EXPECT--
