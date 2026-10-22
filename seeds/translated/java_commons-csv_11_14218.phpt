--TEST--
Translated seed (inferredbugs-java: commons-csv/11)
--FILE--
<?php

class CSVParser {
    const TT_TOKEN = 1;
    const TT_EOF = 2;
    const TT_EORECORD = 3;
    private $input;
    private $currentLine;
    private $currentPos;

    public function __construct($input) {
        $this->input = $input;
        $this->currentLine = '';
        $this->currentPos = 0;
    }

    public function nextToken() {
        if ($this->currentPos >= strlen($this->currentLine)) {
            if ($this->readLine() === false) return [self::TT_EOF, ''];
        }
        $token = '';
        while ($this->currentPos < strlen($this->currentLine) && $this->currentLine[$this->currentPos] !== ',') {
            $token .= $this->currentLine[$this->currentPos++];
        }
        $this->currentPos++; // Skip comma
        return [self::TT_TOKEN, trim($token)];
    }

    private function readLine() {
        $this->currentLine = fgets($this->input);
        $this->currentPos = 0;
        return $this->currentLine !== false;
    }
}

class TestCSVParser extends CSVParser {
    public function testNextToken() {
        $token = $this->nextToken();
        return $token[0] . ';' . $token[1] . ';';
    }
}

$code = "abc,def, hijk,  lmnop,   qrst,uv ,wxy   ,z , ,";
$parser = new TestCSVParser(fopen("php://memory", "r+"));
fwrite($parser->input, $code);
rewind($parser->input);
for ($i = 0; $i < 9; $i++) {
    echo $parser->testNextToken();
}
echo $parser->testNextToken();
echo $parser->testNextToken();
--EXPECT--
