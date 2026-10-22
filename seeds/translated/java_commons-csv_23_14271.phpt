--TEST--
Translated seed (inferredbugs-java: commons-csv/23)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $index = 0;

    public function __construct($input) {
        $this->rows = array_map('str_getcsv', explode("\n", $input));
    }

    public function getRecord() {
        return $this->index < count($this->rows) ? $this->rows[$this->index++] : null;
    }

    public function getRecords() {
        return $this->rows;
    }
}

$code = "one,two,three\n" 
    . "'',''\n"       
    . "/',/'\n"       
    . "'/'','/''\n"   
    . "'''',''''\n"   
    . "/,,/,\n"       
    . "//,//\n"       
    . "'//','//'\n"   
    . "   8   ,   \"quoted \"\" /\" // string\"   \n"     
    . "9,   /\n   \n";

$res = [
    ["one", "two", "three"],
    ["", ""],
    ["'", "'"],
    ["'", "'"],
    ["'", "'"],
    [",", ","],
    ["/", "/"],
    ["/", "/"],
    ["   8   ", "   \"quoted \"\" \" / string\"   "],
    ["9", "   \n   "],
];

$parser = new CSVParser($code);
$tmp = $parser->getRecords();
foreach ($res as $index => $expected) {
    if ($tmp[$index] !== $expected) {
        exit(1);
    }
}
--EXPECT--
