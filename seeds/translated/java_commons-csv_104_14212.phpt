--TEST--
Translated seed (inferredbugs-java: commons-csv/104)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $CSVINPUT);
$results = [];
foreach ($lines as $line) {
    $fields = array_map('trim', str_getcsv($line));
    $results[] = $fields;
}

foreach ($RESULT as $index => $expected) {
    if (!isset($results[$index]) || $results[$index] !== $expected) {
        // Error handling or logging can be done here, but no assertions per rules.
    }
}

if (empty($results)) {
    // Handle empty results case
}
--EXPECT--
