--TEST--
Translated seed (inferredbugs-java: commons-csv/134)
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

$result = [
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

$records = array_map('str_getcsv', explode("\n", $csvInput));
$records = array_filter($records); // Remove empty lines

foreach ($records as $record) {
    $record = array_map('trim', $record);
    print_r($record);
}
--EXPECT--
