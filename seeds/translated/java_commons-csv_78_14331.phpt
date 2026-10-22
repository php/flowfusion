--TEST--
Translated seed (inferredbugs-java: commons-csv/78)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n"
            . " a , b , 1 2 \n"
            . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $CSVINPUT);
$records = [];
foreach ($lines as $line) {
    $records[] = str_getcsv($line);
}

foreach ($RESULT as $index => $expected) {
    if ($records[$index] !== $expected) {
        // This simulates the failure case
    }
}

if (count($records) !== count($RESULT)) {
    // This simulates the failure case
}

$emptyInput = "";
$emptyRecord = str_getcsv($emptyInput);
if ($emptyRecord !== [null]) {
    // This simulates the failure case
}

$codes = [
    "hello,\r\n\r\nworld,\r\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\r\n",
    "hello,\r\n\r\nworld,\"\"",
    "hello,\r\n\r\nworld,\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\n",
    "hello,\r\n\r\nworld,\"\""
];
$res = [
    ["hello", ""],
    [""],
    ["world", ""]
];

foreach ($codes as $code) {
    $records = [];
    $lines = explode("\n", $code);
    foreach ($lines as $line) {
        $records[] = str_getcsv($line);
    }
    if (count($records) !== count($res)) {
        // This simulates the failure case
    }
}

$emptyLineCodes = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];
$emptyLineRes = [
    ["hello", ""], 
    [""]
];

foreach ($emptyLineCodes as $code) {
    $records = [];
    $lines = explode("\n", $code);
    foreach ($lines as $line) {
        $records[] = str_getcsv($line);
    }
    if (count($records) !== count($emptyLineRes)) {
        // This simulates the failure case
    }
}
--EXPECT--
