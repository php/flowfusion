--TEST--
Translated seed (inferredbugs-java: commons-csv/121)
--FILE--
<?php

function parseCSV($input) {
    $lines = explode("\n", $input);
    $result = [];

    foreach ($lines as $line) {
        if (trim($line) === '') continue; // ignore empty lines
        $result[] = str_getcsv($line);
    }
    
    return $result;
}

$input = "a,b,c,d\n"
        . " a , b , 1 2 \n"
        . "\"foo baar\", b,\n"
        . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$records = parseCSV($input);
foreach ($records as $record) {
    echo implode(", ", $record) . "\n";
}
--EXPECT--
