--TEST--
Translated seed (inferredbugs-java: commons-csv/108)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$data = [];

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '') continue;
    $data[] = str_getcsv($line);
}

foreach ($result as $index => $expected) {
    if (!isset($data[$index]) || $data[$index] !== $expected) {
        exit(1);
    }
}
--EXPECT--
