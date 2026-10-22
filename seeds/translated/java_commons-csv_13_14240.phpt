--TEST--
Translated seed (inferredbugs-java: commons-csv/13)
--FILE--
<?php

class CSVParser {
    const TT_TOKEN = 1;
    const TT_EOF = 0;
    const TT_EORECORD = 2;

    private $lines;
    private $currentLine;

    function __construct($input) {
        $this->lines = explode("\n", $input);
        $this->currentLine = 0;
    }

    function testNextToken() {
        if ($this->currentLine >= count($this->lines)) {
            return self::TT_EOF . ";;";
        }
        $tokens = str_getcsv(trim($this->lines[$this->currentLine]));
        if (count($tokens) === 0) {
            $this->currentLine++;
            return self::TT_EORECORD . ";;";
        }
        $result = self::TT_TOKEN . ";" . array_shift($tokens) . ";";
        if (count($tokens) === 0) {
            $this->currentLine++;
            return $result;
        }
        return $result;
    }
}

$code = "1,2,3,\na,b x,c\n#foo\n\nd,e,\n\n";
$parser = new CSVParser($code);

echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
echo $parser->testNextToken() . "\n";
--EXPECT--
