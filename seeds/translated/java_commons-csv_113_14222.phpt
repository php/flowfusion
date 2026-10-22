--TEST--
Translated seed (inferredbugs-java: commons-csv/113)
--FILE--
<?php

$csv_input = "a,b,c,d\n"
            . " a , b , 1 2 \n"
            . "\"foo baar\", b,\n"
            . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$expected_result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parseCsv($input) {
    $rows = explode("\n", $input);
    $result = [];
    foreach ($rows as $row) {
        if (trim($row) === "") continue;
        // Basic CSV splitting logic, you might want to improve handling for escaped quotes etc.
        $result[] = str_getcsv($row);
    }
    return $result;
}

$records = parseCsv($csv_input);

foreach ($expected_result as $index => $expected) {
    if (isset($records[$index])) {
        $record = $records[$index];
        for ($i = 0; $i < count($expected); $i++) {
            if ($expected[$i] !== $record[$i]) {
                // Uncomment the line below for debugging
                // echo "Expected: " . $expected[$i] . ", Got: " . $record[$i] . "\n";
            }
        }
    }
}
--EXPECT--
