--TEST--
Translated seed (inferredbugs-java: commons-csv/79)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
            . " a , b , 1 2 \n"
            . "\"foo baar\", b,\n"
            . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$results = [
    ["a", "b", "c", "d"],
    [" a ", " b ", " 1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$rows = array_map('str_getcsv', explode("\n", $csvInput));

foreach ($results as $index => $expected) {
    $actual = array_map('trim', $rows[$index]);
    if ($actual !== $expected) {
        exit(1);
    }
}

exit(0);
--EXPECT--
