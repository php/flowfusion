--TEST--
Translated seed (inferredbugs-java: commons-csv/128)
--FILE--
<?php

$csvInput = "one,two,three\n" .
            "'',''\n" .
            "/',/'\n" .
            "'/'','/''\n" .
            "'''',''''\n" .
            "/,,/,\n" .
            "//,//\n" .
            "'//','//'\n" .
            "   8   ,   \"quoted \"\" /\" // string\"   \n" .
            "9,   /\n   \n";

$res = [
    ["one", "two", "three"],
    ["", ""],
    ["'", "'"],
    ["'", "'"],
    ["'", "'"],
    [",", ","],
    ["/", "/"],
    ["/", "/"],
    ["   8   ", "   \"quoted \"\" /\" / string\"   "],
    ["9", "   \n   "],
];

$records = parseCsv($csvInput);
foreach ($records as $index => $record) {
    if ($record !== $res[$index]) {
        echo "Records do not match expected result at index $index\n";
    }
}

function parseCsv($input) {
    $lines = explode("\n", $input);
    $results = [];
    foreach ($lines as $line) {
        $results[] = parseLine($line);
    }
    return $results;
}

function parseLine($line) {
    $line = trim($line);
    $fields = [];
    $currentField = '';
    $quote = false;
    for ($i = 0; $i < strlen($line); $i++) {
        $char = $line[$i];
        if ($char === "'") {
            $quote = !$quote;
        } elseif ($char === ',' && !$quote) {
            $fields[] = $currentField;
            $currentField = '';
        } else {
            $currentField .= $char;
        }
    }
    if ($currentField !== '') {
        $fields[] = $currentField;
    }
    return $fields;
}
--EXPECT--
