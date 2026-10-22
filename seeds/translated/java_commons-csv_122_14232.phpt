--TEST--
Translated seed (inferredbugs-java: commons-csv/122)
--FILE--
<?php

$input = "one,two,three\n" 
       . "'',''\n"       
       . "/',/'\n"       
       . "'/'','/''\n"   
       . "'''',''''\n"   
       . "/,,/,\n"       
       . "//,//\n"       
       . "'//','//'\n"   
       . "   8   ,   \"quoted \"\" /\" // string\"   \n"     
       . "9,   /\n   \n";

$expected = [
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

$lines = explode("\n", $input);
$records = [];

foreach ($lines as $line) {
    $records[] = array_map('trim', str_getcsv($line, ",", "'"));
}

if (count($records) > 0) {
    foreach ($records as $index => $record) {
        if ($record !== $expected[$index]) {
            exit("Record mismatch at index $index");
        }
    }
}

$emptyInput = "\nfoo,baar\n\r\n,\n\n,world\r\n\n";
$emptyRecords = array_filter(array_map('str_getcsv', explode("\n", $emptyInput)));

if (count($emptyRecords) != 3) {
    exit("Failed to parse empty lines correctly");
}

$eofInput = "hello,\r\n\r\nworld,\r\n";
$eofRecords = array_filter(array_map('str_getcsv', explode("\n", $eofInput)));

if (count($eofRecords) != 2) {
    exit("Failed to parse end of file correctly");
}

$csv57Input = "";
$csv57Records = array_filter(array_map('str_getcsv', explode("\n", $csv57Input)));

if (count($csv57Records) !== 0) {
    exit("Expected zero records for empty input");
}

$lineFeedInput = "foo\nbaar,\nhello,world\n,kanu";
$lineFeedRecords = array_filter(array_map('str_getcsv', explode("\n", $lineFeedInput)));

if (count($lineFeedRecords) != 4) {
    exit("Failed to parse line feed endings correctly");
}

$carriageReturnInput = "foo\rbaar,\rhello,world\r,kanu";
$carriageReturnRecords = array_filter(array_map('str_getcsv', explode("\n", str_replace("\r\n", "\n", $carriageReturnInput))));

if (count($carriageReturnRecords) != 4) {
    exit("Failed to parse carriage return endings correctly");
}
--EXPECT--
