--TEST--
Translated seed (inferredbugs-java: commons-csv/15)
--FILE--
<?php

class CSVParser {
    private $lines;
    private $currentLine;
    private $delimiter;
    private $encapsulator;

    public function __construct($input, $delimiter = ',', $encapsulator = '"') {
        $this->lines = explode("\n", $input);
        $this->currentLine = 0;
        $this->delimiter = $delimiter;
        $this->encapsulator = $encapsulator;
    }

    public function getLine() {
        if ($this->currentLine >= count($this->lines)) return null;
        $line = $this->lines[$this->currentLine++];
        return str_getcsv($line, $this->delimiter, $this->encapsulator);
    }
}

$code =
    "a,b,c,d\n"
    . " a , b ,  1  2 \n"
    . "\"foo baar\", b,\n"
    . "   \"foo\n,,\n\"\",,\n\"\"\",d,e\n";

$parser = new CSVParser($code);
while (($line = $parser->getLine()) !== null) {
    print_r($line);
}

$emptyCode = "\nfoo,baar\n\r\n,\n\n,world\r\n\n";
$emptyParser = new CSVParser($emptyCode);
while (($line = $emptyParser->getLine()) !== null) {
    print_r($line);
}
--EXPECT--
