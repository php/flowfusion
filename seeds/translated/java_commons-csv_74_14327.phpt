--TEST--
Translated seed (inferredbugs-java: commons-csv/74)
--FILE--
<?php

$input = "a,b,c,d\n"
        . " a , b , 1 2 \n"
        . "\"foo baar\", b,\n"
        . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

function parseCsv($input) {
    $lines = explode("\n", $input);
    $result = [];
    foreach ($lines as $line) {
        $result[] = str_getcsv($line);
    }
    return $result;
}

$records = parseCsv($input);
foreach ($records as $record) {
    var_dump($record);
}
--EXPECT--
