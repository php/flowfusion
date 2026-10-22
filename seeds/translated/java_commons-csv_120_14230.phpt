--TEST--
Translated seed (inferredbugs-java: commons-csv/120)
--FILE--
<?php

$code = "one,two,three\n" .
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

$rows = explode("\n", $code);
$records = [];
foreach ($rows as $row) {
    if (trim($row) === '') {
        continue;
    }
    $records[] = str_getcsv($row, ',', '"');
}

if (count($records) > 0) {
    foreach ($res as $index => $expected) {
        if ($records[$index] !== $expected) {
            exit(1);
        }
    }
}

$code2 = " , , \n" .
          " \t ,  , \n" .
          " // , /, , /,\n";

$res2 = [
    [" ", " ", " "],
    [" \t ", "  ", " "],
    [" / ", " , ", " ,"],
];

$rows2 = explode("\n", $code2);
$records2 = [];
foreach ($rows2 as $row) {
    if (trim($row) === '') {
        continue;
    }
    $records2[] = str_getcsv($row, ',', '"');
}

if (count($records2) > 0) {
    foreach ($res2 as $index => $expected) {
        if ($records2[$index] !== $expected) {
            exit(1);
        }
    }
}
--EXPECT--
