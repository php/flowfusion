--TEST--
Translated seed (inferredbugs-java: commons-csv/82)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", trim($csvInput));
$parsedRecords = [];

foreach ($lines as $line) {
    $line = trim($line);
    if ($line === "") {
        continue;
    }
    
    if (preg_match_all('/("([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|([^,"\\\\\s]+))/', $line, $matches)) {
        $record = array_map(function($item) {
            return trim($item, '" ');
        }, array_filter(array_merge($matches[2], $matches[3])));
        $parsedRecords[] = $record;
    }
}

foreach ($result as $i => $expected) {
    for ($j = 0; $j < count($expected); $j++) {
        if (isset($parsedRecords[$i][$j]) && $parsedRecords[$i][$j] !== $expected[$j]) {
            exit(1);
        }
    }
}

exit(0);
--EXPECT--
