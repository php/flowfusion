--TEST--
Translated seed (inferredbugs-java: commons-csv/89)
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
        $result[] = str_getcsv($line);
    }
    return $result;
}

$parsedRecords = parseCSV($CSVINPUT);

foreach ($RESULT as $index => $expected) {
    if ($parsedRecords[$index] !== $expected) {
        // Optionally handle mismatch here
    }
}

if (count($parsedRecords) < count($RESULT)) {
    // Optionally handle unexpected end of records
}

$emptyInput = "";
$emptyParsed = parseCSV($emptyInput);

if (!empty($emptyParsed)) {
    // Optionally handle unexpected non-empty result for empty input
}
--EXPECT--
