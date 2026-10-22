--TEST--
Translated seed (inferredbugs-java: commons-csv/38)
--FILE--
<?php

class CSVParser {
    private $data;
    private $index = 0;

    public function __construct($input) {
        $this->data = array_filter(array_map('str_getcsv', explode("\n", $input)));
    }

    public function getRecord() {
        return $this->data[$this->index++] ?? null;
    }

    public function getRecords() {
        return $this->data;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$expected = [
    ["a", "b", "c", "d"],
    [" a ", " b ", " 1 2 "],
    ["foo baar", " b ", ""],
    ["foo\n,,\n\",,\n\"", "d", "e"]
];

$parser = new CSVParser($code);
foreach ($expected as $record) {
    $result = $parser->getRecord();
}
$result = $parser->getRecord();

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
    $result = $parser->getRecords();
}

$codes = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

foreach ($codes as $code) {
    $parser = new CSVParser($code);
    $result = $parser->getRecords();
}

$code = "one,two,three\n'on\\\"e,two\none,\"tw\\\"o\"\n";
$expected = [
    ["one", "two", "three"],
    ["on\\\"e", "two"],
    ["one", "tw\"o"]
];

$parser = new CSVParser($code);
foreach ($expected as $record) {
    $result = $parser->getRecord();
}
--EXPECT--
