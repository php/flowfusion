--TEST--
Translated seed (inferredbugs-java: commons-csv/31)
--FILE--
<?php

$code = "a,b,c,d\n"
      . " a , b , 1 2 \n"
      . "\"foo baar\", b,\n"
      . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$res = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$lines = explode("\n", trim($code));

foreach ($lines as $line) {
    $record = str_getcsv($line);
    if ($record) {
        $expected = array_shift($res);
        $equal = ($expected === $record);
        if (!$equal) {
            var_dump($expected, $record);
        }
    }
}
--EXPECT--
