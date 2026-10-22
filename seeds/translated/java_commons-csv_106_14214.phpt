--TEST--
Translated seed (inferredbugs-java: commons-csv/106)
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

$lines = explode("\n", $csvInput);
$records = [];
foreach ($lines as $line) {
    $record = str_getcsv($line);
    $records[] = $record;
}

for ($i = 0; $i < count($result); $i++) {
    if ($records[$i] !== $result[$i]) {
        exit(1); // Mimic test failure
    }
}

if (count($records) < count($result)) {
    exit(1); // Mimic test failure
}
--EXPECT--
