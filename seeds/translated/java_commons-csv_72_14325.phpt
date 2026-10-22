--TEST--
Translated seed (inferredbugs-java: commons-csv/72)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$rows = explode("\n", $csvInput);
$parsedResults = [];

foreach ($rows as $row) {
    // Trim spaces and split by comma, consider quotes for encapsulated values
    $parsedResults[] = array_map('trim', str_getcsv($row));
}

// Check if the length of results matches
if (count($parsedResults) === count($result)) {
    foreach ($parsedResults as $i => $parsedRow) {
        if ($parsedRow !== $result[$i]) {
            exit(1);
        }
    }
}

exit(0);
--EXPECT--
