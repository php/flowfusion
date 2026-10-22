--TEST--
Translated seed (inferredbugs-java: commons-csv/39)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $currentLine;
    
    public function __construct($input) {
        $this->lines = explode("\n", $input);
        $this->currentLine = 0;
    }

    public function getRecords() {
        $records = [];
        while ($this->currentLine < count($this->lines)) {
            $line = $this->lines[$this->currentLine++];
            if (trim($line) == '') continue; // Skip empty lines
            $records[] = str_getcsv($line);
        }
        return $records;
    }
}

$code = "foo,bar\n\nhello,world\n\n";
$parser = new CSVParser($code);
$records = $parser->getRecords();
foreach ($records as $record) {
    print_r($record);
}
--EXPECT--
