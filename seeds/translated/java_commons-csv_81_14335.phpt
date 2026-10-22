--TEST--
Translated seed (inferredbugs-java: commons-csv/81)
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

function parseCSV($input) {
    return array_map('str_getcsv', explode("\n", $input));
}

$records = parseCSV($CSVINPUT);
foreach ($RESULT as $index => $expected) {
    $actual = $records[$index];
}

$emptyParser = parseCSV("");
$nullValue = count($emptyParser) === 0 ? null : $emptyParser[0];

$testEndOfFileBehavior = [
    "hello,\r\n\r\nworld,\r\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\r\n",
    "hello,\r\n\r\nworld,\"\"",
    "hello,\r\n\r\nworld,\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\n",
    "hello,\r\n\r\nworld,\"\""
];

foreach ($testEndOfFileBehavior as $code) {
    $records = parseCSV($code);
}

$testEmptyLineBehavior = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

foreach ($testEmptyLineBehavior as $code) {
    $records = parseCSV($code);
}

$testIgnoreEmptyLines = "\nfoo,baar\n\r\n,\n\n,world\r\n\n";
$records = parseCSV($testIgnoreEmptyLines);
--EXPECT--
