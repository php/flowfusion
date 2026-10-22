--TEST--
Translated seed (inferredbugs-java: commons-csv/35)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $currentIndex = 0;

    public function __construct($data) {
        $this->rows = array_map('str_getcsv', explode("\n", $data));
    }

    public function getRecord() {
        return $this->currentIndex < count($this->rows) ? $this->rows[$this->currentIndex++] : null;
    }

    public function getRecords() {
        return $this->rows;
    }
}

$code = "a,b,c,d\n a , b , 1 2 \n\"foo baar\", b,\n   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";
$expectedResults = [
    ["a", "b", "c", "d"],
    [" a ", " b ", " 1 2 "],
    ["foo baar", " b ", ""],
    ["foo\n,,\n\"\",,\n\"\"\"", "d", "e"]
];

$parser = new CSVParser($code);
foreach ($expectedResults as $expected) {
    $result = $parser->getRecord();
    if ($result !== null && $result !== $expected) {
        exit(1);
    }
}

$result = $parser->getRecord();
if ($result !== null) {
    exit(1);
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

$expectedRes = [
    ["hello", ""],
    [""],
    ["world", ""]
];

foreach ($codes as $code) {
    $parser = new CSVParser($code);
    $result = $parser->getRecords();
    if ($result !== $expectedRes) {
        exit(1);
    }
}

$emptyLineCodes = [
    "hello,\r\n\r\n\r\n",
    "hello,\n\n\n",
    "hello,\"\"\r\n\r\n\r\n",
    "hello,\"\"\n\n\n"
];

$expectedEmptyRes = [
    ["hello", ""],
    [""]
];

foreach ($emptyLineCodes as $code) {
    $parser = new CSVParser($code);
    $result = $parser->getRecords();
    if ($result !== $expectedEmptyRes) {
        exit(1);
    }
}
--EXPECT--
