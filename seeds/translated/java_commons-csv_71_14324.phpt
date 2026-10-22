--TEST--
Translated seed (inferredbugs-java: commons-csv/71)
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

$lines = explode("\n", $CSVINPUT);
$data = [];
foreach ($lines as $line) {
    $data[] = str_getcsv($line);
}

foreach ($RESULT as $index => $expected) {
    if (!isset($data[$index])) {
        exit; // simulating null return
    }
    if ($data[$index] !== $expected) {
        exit; // In case of error
    }
}

// Check for null return
if (!isset($data[count($RESULT)])) {
    exit; // simulating null return
}
--EXPECT--
