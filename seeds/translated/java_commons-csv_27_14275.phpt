--TEST--
Translated seed (inferredbugs-java: commons-csv/27)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $currentLine = 0;

    public function __construct($input) {
        $this->lines = array_map('str_getcsv', explode("\n", $input));
    }

    public function getRecords() {
        return $this->lines;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$expectedResults = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$parser = new CSVParser($code);
$results = $parser->getRecords();

foreach ($expectedResults as $expected) {
    if ($results) {
        $result = array_shift($results);
        if ($result !== $expected) {
            exit(1);
        }
    } else {
        exit(1);
    }
}

exit(0);
--EXPECT--
