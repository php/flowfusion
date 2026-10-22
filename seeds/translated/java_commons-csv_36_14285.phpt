--TEST--
Translated seed (inferredbugs-java: commons-csv/36)
--FILE--
<?php

class CSVParser {
    private $data;
    private $pos = 0;

    public function __construct($string) {
        $this->data = array_map('str_getcsv', explode("\n", $string));
    }

    public function getRecords() {
        return array_slice($this->data, $this->pos);
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$parser = new CSVParser($code);
$records = $parser->getRecords();
foreach ($records as $record) {
    echo implode(',', $record) . "\n";
}
--EXPECT--
