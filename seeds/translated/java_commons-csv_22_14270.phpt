--TEST--
Translated seed (inferredbugs-java: commons-csv/22)
--FILE--
<?php

$codes = [
    "one,two,three\n" 
    . "'',''\n"       
    . "/',/'\n"       
    . "'/'','/''\n"   
    . "'''',''''\n"   
    . "/,,/,\n"       
    . "//,//\n"       
    . "'//','//'\n"   
    . "   8   ,   \"quoted \\\"\\\" /\\\" // string\"   \n"     
    . "9,   /\n   \n",
];

$res = [
    ["one", "two", "three"],
    ["", ""],
    ["'", "'"],
    ["'", "'"],
    ["'", "'"],
    [",", ","],
    ["/", "/"],
    ["/", "/"],
    ["   8   ", "   \"quoted \\\"\\\" /\\\" // string\"   "],
    ["9", "   \n   "],
];

foreach ($codes as $code) {
    $rows = array_map('str_getcsv', explode("\n", trim($code)));
    foreach ($res as $index => $expected) {
        if (!isset($rows[$index]) || $rows[$index] !== $expected) {
            exit(1);
        }
    }
}
--EXPECT--
