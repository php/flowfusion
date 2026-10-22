--TEST--
Translated seed (inferredbugs-java: commons-csv/12)
--FILE--
<?php

class CSVParser {
    const TT_TOKEN = 1;
    const TT_EOF = 0;
    const TT_EORECORD = 2;
    
    private $tokens = [];
    private $index = 0;

    public function __construct($input) {
        $this->tokens = $this->tokenize($input);
    }

    private function tokenize($input) {
        $lines = explode("\n", $input);
        $tokens = [];
        foreach ($lines as $line) {
            $lineTokens = preg_split('/,/', $line);
            foreach ($lineTokens as $token) {
                $tokens[] = trim($token);
            }
            $tokens[] = '';
        }
        return $tokens;
    }

    public function nextToken() {
        if ($this->index < count($this->tokens)) {
            $token = $this->tokens[$this->index++];
            return ($token === '') ? self::TT_EOF : self::TT_TOKEN . ';' . $token . ';';
        }
        return self::TT_EOF;
    }
}

$code = "abc,def, hijk,  lmnop,   qrst,uv ,wxy   ,z , ,";
$parser = new CSVParser($code);
for ($i = 0; $i < 9; $i++) {
    echo $parser->nextToken();
}
echo $parser->nextToken();

$code = "1,2,3,\na,b x,c\n#foo\n\nd,e,\n\n";
$parser = new CSVParser($code);
for ($i = 0; $i < 10; $i++) {
    echo $parser->nextToken();
}

$code = "a,\\,,b\n\\,,";
$parser = new CSVParser($code);
for ($i = 0; $i < 6; $i++) {
    echo $parser->nextToken();
}

$code = "a,\"foo\",b\na,   \" foo\",b\na,\"foo \"  ,b\na,  \" foo \"  ,b";
$parser = new CSVParser($code);
for ($i = 0; $i < 8; $i++) {
    echo $parser->nextToken();
}

$code = "a,\"foo\n\",b\n\"foo\n  baar ,,,\"\n\"\n\t \n\"";
$parser = new CSVParser($code);
for ($i = 0; $i < 5; $i++) {
    echo $parser->nextToken();
}

$code = "a;'b and '' more\n'\n!comment;;;;\n;;";
$parser = new CSVParser($code);
for ($i = 0; $i < 3; $i++) {
    echo $parser->nextToken();
}
--EXPECT--
