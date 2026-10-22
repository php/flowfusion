--TEST--
Translated seed (inferredbugs-java: commons-csv/117)
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
    $lines = explode("\n", $input);
    $result = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === "") continue;
        $result[] = str_getcsv($line);
    }
    return $result;
}

$records = parseCSV($CSVINPUT);
foreach ($RESULT as $index => $expected) {
    if ($records[$index] !== $expected) {
        exit("Test failed at line " . ($index + 1));
    }
}
--EXPECT--
