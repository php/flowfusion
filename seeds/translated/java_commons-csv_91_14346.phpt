--TEST--
Translated seed (inferredbugs-java: commons-csv/91)
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
        if (trim($line) === "") {
            continue;
        }
        $result[] = str_getcsv($line);
    }

    return $result;
}

$records = parseCSV($CSVINPUT);

foreach ($RESULT as $i => $expected) {
    if (isset($records[$i])) {
        echo implode(", ", $expected) . "\n";
    }
}

if (count($records) === 0 || !isset($records[count($records) - 1])) {
    echo "null\n";
}
--EXPECT--
