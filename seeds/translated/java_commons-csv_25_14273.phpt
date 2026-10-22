--TEST--
Translated seed (inferredbugs-java: commons-csv/25)
--FILE--
<?php
class CSVParser {
    private $lines;
    private $index = 0;

    public function __construct($input) {
        $this->lines = preg_split('/\r\n|\n|\r/', $input);
    }

    public function getRecords() {
        $records = [];
        while ($this->index < count($this->lines)) {
            $record = $this->parseLine($this->lines[$this->index]);
            if ($record !== null) {
                $records[] = $record;
            }
            $this->index++;
        }
        return $records;
    }

    private function parseLine($line) {
        if (trim($line) === '') return null;
        return str_getcsv($line);
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$parser = new CSVParser($code);
$data = $parser->getRecords();
foreach ($data as $record) {
    echo implode(", ", $record) . "\n";
}
--EXPECT--
