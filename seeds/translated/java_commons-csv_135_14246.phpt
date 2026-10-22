--TEST--
Translated seed (inferredbugs-java: commons-csv/135)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
           . " a , b , 1 2 \n"
           . "\"foo baar\", b,\n"
           . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$expectedResult = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCsv($input) {
    $rows = array_filter(explode("\n", $input));
    $result = [];
    
    foreach ($rows as $row) {
        $result[] = str_getcsv($row);
    }
    
    return $result;
}

$records = parseCsv($csvInput);
$recordsCount = count($records);

for ($i = 0; $i < $recordsCount; $i++) {
    $record = $records[$i];
    foreach ($record as $value) {
        echo $value . " ";
    }
    echo "\n";
}
--EXPECT--
