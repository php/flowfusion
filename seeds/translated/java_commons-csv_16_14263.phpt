--TEST--
Translated seed (inferredbugs-java: commons-csv/16)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $currentLineIndex = 0;
    private $currentLineData = [];
    private $delimiter = ',';

    public function __construct($input) {
        $this->lines = explode("\n", $input);
    }

    private function getNextLine() {
        if ($this->currentLineIndex < count($this->lines)) {
            return $this->lines[$this->currentLineIndex++];
        }
        return false;
    }

    private function parseLine($line) {
        return explode($this->delimiter, $line);
    }

    public function getRecords() {
        $records = [];
        while ($line = $this->getNextLine()) {
            $records[] = $this->parseLine($line);
        }
        return $records;
    }
}

$code = "a,b,c\n1,2,3\nx,y,z";
$parser = new CSVParser($code);
$data = $parser->getRecords();
foreach ($data as $row) {
    echo implode(',', $row) . "\n";
}
--EXPECT--
