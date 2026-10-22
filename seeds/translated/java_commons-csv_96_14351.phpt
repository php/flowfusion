--TEST--
Translated seed (inferredbugs-java: commons-csv/96)
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

$rows = array_map("str_getcsv", explode("\n", $csvInput));
$records = [];

foreach ($rows as $row) {
    if (count($row) > 0) {
        $records[] = array_map('trim', $row);
    }
}

foreach ($result as $index => $expected) {
    if (!isset($records[$index])) {
        break;
    }
    $actual = $records[$index];
    foreach ($expected as $key => $value) {
        if ($actual[$key] !== $value) {
            exit(1);
        }
    }
}

if (count($records) !== count($result)) {
    exit(1);
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
    $rows = array_map("str_getcsv", explode("\n", $code));
    $records = [];
    foreach ($rows as $row) {
        if (count($row) > 0) {
            $records[] = array_map('trim', $row);
        }
    }
    if (count($records) !== count($res)) {
        exit(1);
    }
    foreach ($res as $index => $expected) {
        if ($records[$index][0] !== $expected[0]) {
            exit(1);
        }
    }
}

$emptyInput = "";
$rows = array_map("str_getcsv", explode("\n", $emptyInput));
$records = [];
foreach ($rows as $row) {
    if (count($row) > 0) {
        $records[] = array_map('trim', $row);
    }
}
if (count($records) > 0) {
    exit(1);
}

$csv57Input = "";
$rows = array_map("str_getcsv", explode("\n", $csv57Input));
$records = [];
foreach ($rows as $row) {
    if (count($row) > 0) {
        $records[] = array_map('trim', $row);
    }
}
if (count($records) !== 0) {
    exit(1);
}
--EXPECT--
