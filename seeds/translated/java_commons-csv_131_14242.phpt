--TEST--
Translated seed (inferredbugs-java: commons-csv/131)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n"
          . " a , b , 1 2 \n"
          . "\"foo baar\", b,\n"
          . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$code = "one,two,three\n" 
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

$records = explode("\n", $code);
foreach ($records as $record) {
    $record = str_getcsv($record, ",", "'", "/");
    if (!empty($record)) {
        print_r($record);
    }
}

$code2 = " , , \n"          
      . " \t ,  , \n"      
      . " // , /, , /,\n";

$res2 = [
    [" ", " ", " "],
    [" \t ", "  ", " "],
    [" / ", " , ", " ,"],
];

$records2 = explode("\n", $code2);
foreach ($records2 as $record) {
    $record = str_getcsv($record, ",", " ", "/");
    if (!empty($record)) {
        print_r($record);
    }
}
--EXPECT--
