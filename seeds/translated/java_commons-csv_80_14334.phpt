--TEST--
Translated seed (inferredbugs-java: commons-csv/80)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
           . " a , b , 1 2 \n"
           . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";   

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '') {
        continue;
    }
    $record = str_getcsv($line);
    for ($i = 0; $i < count($record); $i++) {
        $record[$i] = trim($record[$i]);
    }
    $expected = $result[array_search($line, $lines)];
    if ($record != $expected) {
        echo "Mismatch: expected " . json_encode($expected) . " but got " . json_encode($record) . "\n";
    }
}
--EXPECT--
