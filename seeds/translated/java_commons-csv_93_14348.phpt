--TEST--
Translated seed (inferredbugs-java: commons-csv/93)
--FILE--
<?php

$csvInput = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$result = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", $csvInput);
$parsedRecords = [];
foreach ($lines as $line) {
    $parsedRecords[] = str_getcsv($line);
}

foreach ($result as $index => $expected) {
    if (!isset($parsedRecords[$index])) break;
    $actual = $parsedRecords[$index];
}

if (empty($parsedRecords)) {
    return;
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
    $parsedRecords = array_filter(array_map('str_getcsv', explode("\n", $code)));
    foreach ($res as $index => $expected) {
        if (!isset($parsedRecords[$index])) break;
        $actual = $parsedRecords[$index];
    }
}

$codes = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

$res = [
    ["hello", ""],
    [""]
];

foreach ($codes as $code) {
    $parsedRecords = array_filter(array_map('str_getcsv', explode("\n", $code)));
    foreach ($res as $index => $expected) {
        if (!isset($parsedRecords[$index])) break;
        $actual = $parsedRecords[$index];
    }
}
--EXPECT--
