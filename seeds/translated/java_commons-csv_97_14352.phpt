--TEST--
Translated seed (inferredbugs-java: commons-csv/97)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$rows = str_getcsv($csvInput, "\n");
foreach ($rows as $index => $row) {
    $values = str_getcsv(trim($row));
    if (isset($result[$index])) {
        foreach ($values as $i => $value) {
            $value = trim($value);
            $result[$index][$i] = $value;
        }
    }
}

foreach ($result as $row) {
    echo implode(',', $row) . "\n";
}
--EXPECT--
