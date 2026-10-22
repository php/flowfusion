--TEST--
Translated seed (inferredbugs-java: commons-csv/94)
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
foreach ($lines as $line) {
    $record = array_map('trim', str_getcsv($line));
    if (empty($record[0]) && count($result) > 0) {
        continue;
    }
    if (count($record) !== count($result[count($result) - 1])) {
        continue;
    }
    $result[] = $record;
}

$resultCount = count($result);
if ($resultCount !== 4) {
    exit(1);
}

foreach ($result as $index => $expected) {
    if ($index === 0) continue;
    if ($result[$index] !== $expected) {
        exit(1);
    }
}

exit(0);
--EXPECT--
