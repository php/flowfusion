--TEST--
Translated seed (inferredbugs-java: commons-csv/40)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $position = 0;

    public function __construct($input) {
        $this->rows = array_map('str_getcsv', explode("\n", trim($input)));
    }

    public function getRecord() {
        return isset($this->rows[$this->position]) ? $this->rows[$this->position++] : null;
    }

    public function getRecords() {
        return $this->rows;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$res = [
    ["a", "b", "c", "d"],
    [" a ", " b ", " 1 2 "],
    ["foo baar", " b ", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$parser = new CSVParser($code);
foreach ($res as $re) {
    $record = $parser->getRecord();
}

$record = $parser->getRecord();
--EXPECT--
