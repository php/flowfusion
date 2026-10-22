--TEST--
Translated seed (inferredbugs-java: commons-csv/26)
--FILE--
<?php

class CSVParser {
    private $data;
    private $index = 0;

    public function __construct($code) {
        $this->data = array_map('str_getcsv', explode("\n", $code));
    }

    public function getRecords() {
        return array_slice($this->data, $this->index++);
    }
}

$code = "a,b,c,d\n"
      . " a , b , 1 2 \n"
      . "\"foo baar\", b,\n"
      . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n"; 

$parser = new CSVParser($code);
$results = [];
while ($record = $parser->getRecords()) {
    $results[] = $record;
}

foreach ($results as $record) {
    echo implode(',', $record) . "\n";
}
--EXPECT--
