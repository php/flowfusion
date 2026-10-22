--TEST--
Translated seed (inferredbugs-java: commons-csv/34)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $index = 0;

    public function __construct($input) {
        $this->rows = array_map('str_getcsv', explode("\n", trim($input)));
    }

    public function getRecord() {
        return isset($this->rows[$this->index]) ? $this->rows[$this->index++] : null;
    }

    public function getRecords() {
        return $this->rows;
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

foreach ($res as $record) {
    $parser->getRecord();
}

$codes = [
    "hello,\r\n\r\nworld,\r\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\r\n",
    "hello,\r\n\r\nworld,\"\"",
    "hello,\r\n\r\nworld,\n",
    "hello,\r\n\r\nworld,",
    "hello,\r\n\r\nworld,\"\"\n",
    "hello,\r\n\r\nworld,\"\""
];

foreach ($codes as $code) {
    $parser = new CSVParser($code);
    $parser->getRecords();
}

$lines = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

foreach ($lines as $line) {
    $parser = new CSVParser($line);
    $parser->getRecords();
}

$code = "one,two,three\n on\\\"e,two\n on\"e,two\n one,\"tw\\\"o\"\n one,\"t\\,wo\"\n one,two,\"th,ree\"\n \"a\\\\\"\n a\\,b\n \"a\\\\,b\"";
$parser = new CSVParser($code);
$parser->getRecords();

$code = " , , \n \t ,  , \n // , /, , /,\n";
$parser = new CSVParser($code);
$parser->getRecords();
--EXPECT--
