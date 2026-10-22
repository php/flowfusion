--TEST--
Translated seed (inferredbugs-java: commons-csv/98)
--FILE--
<?php

$csvInputs = [
    "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n",
    "value1,value2,value3,value4\r\na,b,c,d\r\n  x,,,\r\n\r\n\"\"\"hello\"\"\",\"  \"\"world\"\"\",\"abc\ndef\",\r\n",
    "foo,baar\r\n\r\nhello,\r\n\r\nworld,\r\n",
    "hello,\r\n\r\nworld,\r\n",
    "hello,\n\n\n",
];

$expectedResults = [
    [
        ["a", "b", "c", "d"],
        ["a", "b", "1 2"],
        ["foo baar", "b", ""],
        ["foo\n,,\n\",,\n\"", "d", "e"]
    ],
    [
        ["value1", "value2", "value3", "value4"],
        ["a", "b", "c", "d"],
        ["  x", "", "", ""],
        [""],
        ["\"hello\"", "  \"world\"", "abc\ndef", ""]
    ],
    [
        ["foo", "baar"],
        [""],
        ["hello", ""],
        [""],
        ["world", ""]
    ],
    [
        ["hello", ""],
        ["world", ""]
    ],
    [
        ["hello", ""],
        []
    ],
];

foreach ($csvInputs as $index => $input) {
    $rows = str_getcsv($input, "\n");
    $result = [];
    foreach ($rows as $row) {
        $result[] = str_getcsv($row);
    }
    if ($result != $expectedResults[$index]) {
        exit(1);
    }
}

$emptyFile = "";
$emptyResult = str_getcsv($emptyFile, "\n");
if (!empty($emptyResult)) {
    exit(1);
}

$singleRowCsv = "foo\r\nbar\r\nbaz\r\n";
$singleRows = str_getcsv($singleRowCsv, "\n");
if (count($singleRows) != 3) {
    exit(1);
}

$multiLineCsv = "\"a\r\n1\",\"b\r\n2\"\n\"c\r\n3\",\"d\r\n4\"";
$multiRows = str_getcsv($multiLineCsv, "\n");
foreach ($multiRows as $row) {
    $rowValues = str_getcsv($row);
    foreach ($rowValues as $value) {
        if (strpos($value, "\r\n") !== false) {
            exit(1);
        }
    }
}

exit(0);
--EXPECT--
