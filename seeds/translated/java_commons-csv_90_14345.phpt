--TEST--
Translated seed (inferredbugs-java: commons-csv/90)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n"
           . " a , b , 1 2 \n"
           . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n"; 

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $CSVINPUT);
foreach ($lines as $line) {
    if (trim($line) === '') continue;
    $record = str_getcsv($line);
    $expected = array_shift($RESULT);
    if ($record !== $expected) {
        exit(1);
    }
}

if (!empty($RESULT)) {
    exit(1);
}
--EXPECT--
