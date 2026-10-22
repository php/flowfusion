--TEST--
Translated seed (inferredbugs-java: commons-csv/75)
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
    ["foo\n,,\n\",,\n\"", "d", "e"],
];

$lines = explode("\n", $CSVINPUT);
foreach ($lines as $line) {
    $record = str_getcsv($line);
    echo json_encode($record) . "\n";
}
--EXPECT--
