--TEST--
Translated seed (inferredbugs-java: commons-csv/29)
--FILE--
<?php

class CSVParser {
    private $rows;
    private $currentIndex = 0;

    public function __construct($input) {
        $this->rows = array_filter(array_map('str_getcsv', explode("\n", $input)));
    }

    public function getRecords() {
        return $this->rows;
    }
}

$codes = [
    "a,b\nc,d\n\n",
    "foo,bar\n\nqux,\n",
    "1,2,3\n4,5,6\n\n7,8,9\n"
];

$results = [
    [["a", "b"], ["c", "d"]],
    [["foo", "bar"], ["qux", ""]],
    [["1", "2", "3"], ["4", "5", "6"], ["7", "8", "9"]]
];

for ($i = 0; $i < count($codes); $i++) {
    $parser = new CSVParser($codes[$i]);
    $tmp = $parser->getRecords();
    foreach ($results[$i] as $key => $expected) {
        if ($tmp[$key] !== $expected) { break; }
    }
}

?>
--EXPECT--
