--TEST--
Translated seed (inferredbugs-java: commons-csv/105)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$expectedResult = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$records = [];
foreach ($lines as $line) {
    $records[] = str_getcsv($line);
}

foreach ($expectedResult as $index => $expected) {
    if (!isset($records[$index])) {
        break;
    }
    $actual = $records[$index];
    for ($i = 0; $i < count($expected); $i++) {
        if (!isset($actual[$i]) || trim($actual[$i]) !== trim($expected[$i])) {
            break 2;
        }
    }
}

foreach (["hello,\r\n\r\nworld,\r\n", "hello,\n\nworld,"] as $code) {
    $lines = explode("\n", $code);
    $records = [];
    foreach ($lines as $line) {
        $records[] = str_getcsv($line);
    }
    $csvCount = count($records);
    if ($csvCount > 0) {
        foreach ($records as $record) {
            foreach ($record as $value) {
                echo $value . " ";
            }
            echo "\n";
        }
    }
}
--EXPECT--
