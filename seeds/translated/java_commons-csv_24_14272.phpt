--TEST--
Translated seed (inferredbugs-java: commons-csv/24)
--FILE--
<?php

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
    $lines = explode("\n", trim($code));
    $result = [];
    foreach ($lines as $line) {
        if (trim($line) !== '') {
            $result[] = str_getcsv($line);
        }
    }
    if (count($result) !== count($res)) {
        exit(1);
    }
    foreach ($res as $i => $expected) {
        if ($result[$i] !== $expected) {
            exit(1);
        }
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
    $lines = explode("\n", trim($code));
    $result = [];
    foreach ($lines as $line) {
        if (trim($line) !== '') {
            $result[] = str_getcsv($line);
        }
    }
    if ($result !== $res) {
        exit(1);
    }
}

$code =
    "one,two,three\n" 
    . "on\\\"e,two\n"
    . "on\"e,two\n"
    . "one,\"tw\\\"o\"\n"
    . "one,\"t\\,wo\"\n"
    . "one,two,\"th,ree\"\n"
    . "\"a\\\\\"\n"
    . "a\\,b\n"
    . "\"a\\\\,b\"";
$res = [
    ["one", "two", "three"],
    ["on\\\"e", "two"],
    ["on\"e", "two"],
    ["one", "tw\"o"],
    ["one", "t\\,wo"],
    ["one", "two", "th,ree"],
    ["a\\\\"],
    ["a\\", "b"],
    ["a\\\\,b"]
];

$lines = explode("\n", trim($code));
$result = [];
foreach ($lines as $line) {
    $result[] = str_getcsv($line);
}
if (count($result) !== count($res)) {
    exit(1);
}
foreach ($res as $i => $expected) {
    if ($result[$i] !== $expected) {
        exit(1);
    }
}
--EXPECT--
