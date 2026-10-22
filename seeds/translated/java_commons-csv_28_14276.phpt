--TEST--
Translated seed (inferredbugs-java: commons-csv/28)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $currentLine = 0;

    public function __construct($input) {
        $this->lines = array_filter(array_map('trim', explode("\n", $input)));
    }

    public function getRecord() {
        if ($this->currentLine >= count($this->lines)) return null;
        return str_getcsv($this->lines[$this->currentLine++]);
    }

    public function getRecords() {
        $records = [];
        while (($record = $this->getRecord()) !== null) {
            $records[] = $record;
        }
        return $records;
    }
}

$code = "a,b,c,d\n"
      . " a , b , 1 2 \n"
      . "\"foo baar\", b,\n"
      . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n"; 

$parser = new CSVParser($code);
$records = $parser->getRecords();

foreach ($records as $record) {
    echo implode(", ", $record) . "\n";
}
--EXPECT--
