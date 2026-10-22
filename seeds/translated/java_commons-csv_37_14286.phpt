--TEST--
Translated seed (inferredbugs-java: commons-csv/37)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $position;

    public function __construct($input) {
        $this->lines = explode("\n", $input);
        $this->position = 0;
    }

    public function getRecord() {
        if ($this->position >= count($this->lines)) {
            return null;
        }
        return str_getcsv(trim($this->lines[$this->position++]));
    }

    public function getRecords() {
        $records = [];
        while (($record = $this->getRecord()) !== null) {
            $records[] = $record;
        }
        return $records;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n\"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$parser = new CSVParser($code);
$res = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

foreach ($res as $re) {
    $result = $parser->getRecord();
}

$result = $parser->getRecord();
--EXPECT--
