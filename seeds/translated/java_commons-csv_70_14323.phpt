--TEST--
Translated seed (inferredbugs-java: commons-csv/70)
--FILE--
<?php

class Token {
    public $type;
    public $content;

    const TOKEN = 'TOKEN';
    const EOF = 'EOF';
    const EORECORD = 'EORECORD';
    const COMMENT = 'COMMENT';

    public function __construct() {
        $this->type = null;
        $this->content = '';
    }
}

class CSVLexer {
    private $input;
    private $format;
    private $index = 0;

    public function __construct($format, $input) {
        $this->format = $format;
        $this->input = $input;
    }

    public function nextToken($token) {
        if ($this->index >= strlen($this->input)) {
            $token->type = Token::EOF;
            return $token;
        }

        $char = $this->input[$this->index];

        if ($char === "\n") {
            $this->index++;
            $token->type = Token::EORECORD;
            return $token;
        }

        if (trim($char) === '') {
            $this->index++;
            return $this->nextToken($token);
        }

        $start = $this->index;

        while ($this->index < strlen($this->input) && $this->input[$this->index] !== ',' && $this->input[$this->index] !== "\n") {
            $this->index++;
        }

        $token->content = substr($this->input, $start, $this->index - $start);
        $token->type = Token::TOKEN;

        if ($this->index < strlen($this->input) && $this->input[$this->index] === ',') {
            $this->index++;
        }

        return $token;
    }
}

$code = "1,2,\n\n3,4\n5,";
$lexer = new CSVLexer([], $code);
$token = new Token();

while ($token->type !== Token::EOF) {
    $token = $lexer->nextToken($token);
}
--EXPECT--
