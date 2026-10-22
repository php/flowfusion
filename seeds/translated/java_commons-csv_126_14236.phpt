--TEST--
Translated seed (inferredbugs-java: commons-csv/126)
--FILE--
<?php

$csvInput = "a,b,c,d\n"
          . " a , b , 1 2 \n"
          . "\"foo baar\", b,\n"
          . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$parsedResult = [];

foreach ($lines as $line) {
    $parsedResult[] = str_getcsv($line);
}

foreach ($parsedResult as $key => $value) {
    if ($value !== $result[$key]) {
        exit(1);
    }
}

$code = "'',''\n"       
     . "/',/'\n"       
     . "'/'','/''\n"   
     . "'''',''''\n"   
     . "/,,/,\n"       
     . "//,//\n"       
     . "'//','//'\n"   
     . "   8   ,   \"quoted \"\" /\" // string\"   \n"
     . "9,   /\n   \n";

$res = [
    [" ", " "],
    [" \t ", "  ", " "],
    [" / ", " , ", " ,"],
];

$lines = explode("\n", $code);
$parsedResult = [];

foreach ($lines as $line) {
    $parsedResult[] = str_getcsv($line);
}

foreach ($parsedResult as $key => $value) {
    if ($value !== $res[$key]) {
        exit(1);
    }
}

$parseCode = "\"a\r\n1\",\"a\r\n2\"" . "\r\n" . "\"b\r\n1\",\"b\r\n2\"" . "\r\n" . "\"c\r\n1\",\"c\r\n2\"";
$parseResult = [
    ["a\r\n1", "a\r\n2"],
    ["b\r\n1", "b\r\n2"],
    ["c\r\n1", "c\r\n2"],
];

$lines = explode("\n", $parseCode);
$parsedResult = [];

foreach ($lines as $line) {
    $parsedResult[] = str_getcsv($line);
}

foreach ($parsedResult as $key => $value) {
    if ($value !== $parseResult[$key]) {
        exit(1);
    }
}

exit(0);
--EXPECT--
