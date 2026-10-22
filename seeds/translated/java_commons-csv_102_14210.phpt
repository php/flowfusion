--TEST--
Translated seed (inferredbugs-java: commons-csv/102)
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

$rows = explode("\n", $csvInput);
$result = [];

foreach ($rows as $row) {
    $result[] = str_getcsv(trim($row));
}

for ($i = 0; $i < count($expectedResult); $i++) {
    if ($result[$i] !== $expectedResult[$i]) {
        echo "Test failed at row " . ($i + 1) . "\n";
        exit(1);
    }
}

echo "All tests passed\n";
--EXPECT--
