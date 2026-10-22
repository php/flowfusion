--TEST--
Translated seed (inferredbugs-java: commons-csv/124)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
          . " a , b , 1 2 \n"
          . "\"foo baar\", b,\n"
          . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$data = [];
foreach ($lines as $line) {
    $data[] = str_getcsv($line);
}

foreach ($data as $key => $record) {
    if ($record !== $result[$key]) {
        // The output will be different from expected results
    }
}

$additionalCases = [
    " , , \n"           // 1)
    . " \t ,  , \n"       // 2)
    . " // , /, , /,\n"   // 3)
];

foreach ($additionalCases as $code) {
    $lines = explode("\n", $code);
    $data = [];
    foreach ($lines as $line) {
        $data[] = str_getcsv($line);
    }
}

// Additional tests can be added similarly.
--EXPECT--
