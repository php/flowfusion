--TEST--
Translated seed (inferredbugs-java: commons-csv/92)
--FILE--
<?php

$CSVINPUT = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$RESULT = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

function parse_csv($input) {
    return array_map("str_getcsv", explode("\n", $input));
}

$records = parse_csv($CSVINPUT);

foreach ($RESULT as $index => $expected) {
    if (!isset($records[$index]) || $records[$index] !== $expected) {
        break;
    }
}

$endRecord = count($records) > count($RESULT) ? null : $records[count($RESULT)];
if ($endRecord !== null) {
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

foreach ($codes as $code) {
    $records = parse_csv($code);
    if (count($records) !== 3) {
        break;
    }
}

$codes = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

foreach ($codes as $code) {
    $records = parse_csv($code);
    if (count($records) !== 3) {
        break;
    }
}

$records = parse_csv("");
if (count($records) !== 0) {
    exit(1);
}

$records = parse_csv("a,b,c\n1,2,3\nx,y,z");
if (count($records) !== 3) {
    exit(1);
}
--EXPECT--
