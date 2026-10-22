--TEST--
Translated seed (inferredbugs-java: commons-csv/32)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $current = 0;

    public function __construct($code) {
        $this->rows = array_map("str_getcsv", explode("\n", trim($code)));
    }

    public function getRecord() {
        return $this->current < count($this->rows) ? $this->rows[$this->current++] : null;
    }
    
    public function getRecords() {
        return $this->rows;
    }
}

$code = "a,b,c,d\n"
    . " a , b , 1 2 \n"
    . "\"foo baar\", b,\n"
    . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$parser = new CSVParser($code);
$res = [
    ["a", "b", "c", "d"],
    [" a ", " b ", " 1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

for ($i = 0; $i < count($res); $i++) {
    $record = $parser->getRecord();
}

$record = $parser->getRecord();
--EXPECT--
