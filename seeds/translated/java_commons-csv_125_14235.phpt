--TEST--
Translated seed (inferredbugs-java: commons-csv/125)
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

$format = [ 'delimiter' => ',', 'quote' => "'", 'escape' => '/', 'ignore_empty' => true ];

function parseCSV($input, $format) {
    $rows = explode("\n", $input);
    $result = [];
    foreach ($rows as $row) {
        $result[] = str_getcsv(trim($row), $format['delimiter'], '"', $format['quote']);
    }
    return $result;
}

$records = parseCSV($code, $format);
foreach ($res as $index => $expected) {
    if ($records[$index] !== $expected) {
        exit("Error at row $index: expected " . json_encode($expected) . " but got " . json_encode($records[$index]));
    }
}

$code2 = "foo\rbaar,\rhello,world\r,kanu";
$records2 = parseCSV($code2, $format);
if (count($records2) !== 4) {
    exit("Expected 4 records but got " . count($records2));
}
--EXPECT--
