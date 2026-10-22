--TEST--
Translated seed (inferredbugs-java: commons-csv/114)
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

$records = array_map('str_getcsv', explode("\n", $csvInput));

foreach ($records as $index => $record) {
    if ($index < count($result)) {
        if ($record !== $result[$index]) {
            echo "Mismatch at line $index\n";
        }
    }
}

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

$records2 = array_map('str_getcsv', explode("\n", $code));

foreach ($records2 as $index => $record) {
    if ($index < count($res)) {
        if ($record !== $res[$index]) {
            echo "Mismatch at line $index in second case\n";
        }
    }
}

$csvEndings = [
    "foo\rbaar,\rhello,world\r,kanu",
    "foo\r\nbaar,\r\nhello,world\r\n,kanu",
    "foo\nbaar,\nhello,world\n,kanu"
];

foreach ($csvEndings as $index => $code) {
    $records = array_map('str_getcsv', explode("\n", $code));
    echo count($records) . "\n";
}

$emptyLines = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];
$resEmpty = [
    ["hello", ""]
];

foreach ($emptyLines as $code) {
    $records = array_map('str_getcsv', explode("\n", $code));
    echo count($records) . "\n";
}
--EXPECT--
