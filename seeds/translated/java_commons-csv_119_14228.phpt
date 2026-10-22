--TEST--
Translated seed (inferredbugs-java: commons-csv/119)
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

$records = str_getcsv($code, "\n");
foreach ($records as $record) {
    $parsed[] = str_getcsv(trim($record), ",", '"', "'");
}

if (count($parsed) > 0) {
    foreach ($parsed as $i => $record) {
        if ($record !== $res[$i]) {
            exit(1);
        }
    }
}

$code = " , , \n"           
      . " \t ,  , \n"       
      . " // , /, , /,\n";

$res2 = [
    [" ", " ", " "],
    [" \t ", "  ", " "],
    [" / ", " , ", " ,"],
];

$records2 = str_getcsv($code, "\n");
foreach ($records2 as $record) {
    $parsed2[] = str_getcsv(trim($record), ",", '"', "/");
}

if (count($parsed2) > 0) {
    foreach ($parsed2 as $i => $record) {
        if ($record !== $res2[$i]) {
            exit(1);
        }
    }
}

$code = "foo\rbaar,\rhello,world\r,kanu";
$records3 = str_getcsv($code, "\n");
if (count($records3) !== 4) {
    exit(1);
}

$code = "foo\r\nbaar,\r\nhello,world\r\n,kanu";
$records4 = str_getcsv($code, "\n");
if (count($records4) !== 4) {
    exit(1);
}

$in = "hello,\r\n\r\n\r\n";
$records5 = str_getcsv($in, "\n");
if (count($records5) !== 1) {
    exit(1);
}
--EXPECT--
