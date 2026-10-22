--TEST--
Translated seed (inferredbugs-java: commons-csv/109)
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
$records = [];

foreach ($lines as $line) {
    $records[] = str_getcsv($line);
}

foreach ($RESULT as $index => $expected) {
    if (isset($records[$index])) {
        if ($records[$index] !== $expected) {
            exit(1); // exit with error if mismatch
        }
    } else {
        exit(1); // exit with error if fewer records
    }
}

exit(0); // successful execution
--EXPECT--
