--TEST--
Translated seed (inferredbugs-java: commons-csv/118)
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
    $rows = explode("\n", $input);
    $result = [];
    foreach ($rows as $row) {
        if (trim($row) === '') continue;
        $result[] = str_getcsv($row);
    }
    return $result;
}

$records = parseCSV($CSVINPUT);
foreach ($records as $i => $record) {
    if ($record !== $RESULT[$i]) {
        echo "Mismatch at row " . ($i + 1) . "\n";
    }
}
--EXPECT--
