--TEST--
Translated seed (inferredbugs-java: commons-csv/33)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $index = 0;

    public function __construct($input) {
        $this->lines = explode("\n", $input);
    }

    public function getRecord() {
        if ($this->index >= count($this->lines)) return null;
        $line = $this->lines[$this->index++];
        return str_getcsv($line);
    }

    public function getRecords() {
        $records = [];
        while (($record = $this->getRecord()) !== null) {
            $records[] = $record;
        }
        return $records;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$res = [
    ["a", "b", "c", "d"],
    ["a", "b", "1 2"],
    ["foo baar", "b", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$parser = new CSVParser($code);
foreach ($res as $re) {
    $record = $parser->getRecord();
}
$record = $parser->getRecord();
--EXPECT--
