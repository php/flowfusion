--TEST--
Translated seed (inferredbugs-java: commons-csv/14)
--FILE--
<?php

class CSVParser {
    const TOKEN = "TOKEN";
    const EORECORD = "EORECORD";
    const EOF = "EOF";

    private $lines;
    private $currentLineIndex;
    private $currentTokenIndex;

    public function __construct($input) {
        $this->lines = explode("\n", $input);
        $this->currentLineIndex = 0;
        $this->currentTokenIndex = 0;
    }

    public function nextToken() {
        if ($this->currentLineIndex >= count($this->lines)) {
            return self::EOF . ";;";
        }

        $line = trim($this->lines[$this->currentLineIndex]);
        if ($line === "") {
            $this->currentLineIndex++;
            return self::EORECORD . ";;";
        }

        $tokens = preg_split('/,/', $line);
        $token = isset($tokens[$this->currentTokenIndex]) ? trim($tokens[$this->currentTokenIndex]) : '';
        $result = $this->currentTokenIndex < count($tokens) ? self::TOKEN . ";$token;" : self::EORECORD . ";;";

        if ($this->currentTokenIndex + 1 < count($tokens)) {
            $this->currentTokenIndex++;
        } else {
            $this->currentTokenIndex = 0;
            $this->currentLineIndex++;
        }

        return $result;
    }
}

$input = "abc,def, hijk,  lmnop,   qrst,uv ,wxy   ,z , ,\n" .
         "1,2,3,\na,b x,c\n#foo\n\nd,e,\n\n" .
         "a,\\,,b\n\\,,\na,\"foo\",b\n" .
         "a;'b and '' more\n'\n!comment;;;;\n;;";

$parser = new CSVParser($input);

while (($token = $parser->nextToken()) !== CSVParser::EOF . ";;") {
    echo $token . "\n";
}
--EXPECT--
