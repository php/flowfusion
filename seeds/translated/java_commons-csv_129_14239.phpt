--TEST--
Translated seed (inferredbugs-java: commons-csv/129)
--FILE--
<?php

$csvInput = "one,two,three\n" 
          . "'',''\n" 
          . "/',/'\n" 
          . "'/'','/''\n" 
          . "'''',''''\n" 
          . "/,,/,\n" 
          . "//,//\n" 
          . "'//','//'\n" 
          . "   8   ,   \"quoted \"\" /\" // string\"   \n" 
          . "9,   /\n   \n";

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

$lines = explode("\n", $csvInput);
$records = [];

foreach ($lines as $line) {
    if (trim($line) === '') continue;
    $records[] = array_map('trim', str_getcsv($line, ','));
}

foreach ($res as $index => $expected) {
    if (isset($records[$index])) {
        foreach ($expected as $key => $value) {
            if ($records[$index][$key] !== $value) {
                echo "Mismatch at record $index, field $key: expected '$value', got '{$records[$index][$key]}'\n";
            }
        }
    }
}

$csvInput2 = " , , \n" 
           . " \t ,  , \n" 
           . " // , /, , /,\n";

$res2 = [
    [" ", " ", " "],
    [" \t ", "  ", " "],
    [" / ", " , ", " ,"],
];

$lines2 = explode("\n", $csvInput2);
$records2 = [];

foreach ($lines2 as $line) {
    if (trim($line) === '') continue;
    $records2[] = array_map('trim', str_getcsv($line, ','));
}

foreach ($res2 as $index => $expected) {
    if (isset($records2[$index])) {
        foreach ($expected as $key => $value) {
            if ($records2[$index][$key] !== $value) {
                echo "Mismatch at record $index, field $key: expected '$value', got '{$records2[$index][$key]}'\n";
            }
        }
    }
}
--EXPECT--
