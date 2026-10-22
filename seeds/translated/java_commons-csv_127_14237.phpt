--TEST--
Translated seed (inferredbugs-java: commons-csv/127)
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
    $lines = explode("\n", $input);
    $result = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $result[] = str_getcsv($line);
    }

    return $result;
}

$records = parseCSV($CSVINPUT);

foreach ($records as $index => $record) {
    if ($record !== $RESULT[$index]) {
        echo "Mismatch at record $index\n";
        print_r($record);
        print_r($RESULT[$index]);
    }
}

$emptyCode = "\nfoo,baar\n\r\n,\n\n,world\r\n\n";
$emptyRecords = parseCSV($emptyCode);
if (count($emptyRecords) !== 3) {
    echo "Failed to ignore empty lines\n";
}

$lineFeedCode = "foo\nbaar,\nhello,world\n,kanu";
$lineFeedRecords = parseCSV($lineFeedCode);
if (count($lineFeedRecords) !== 4) {
    echo "Failed to parse line feed endings\n";
}
--EXPECT--
