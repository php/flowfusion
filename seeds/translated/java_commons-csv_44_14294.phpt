--TEST--
Translated seed (inferredbugs-java: commons-csv/44)
--FILE--
<?php

class Token {
    public $type;
    public $content;

    const TOKEN = 'TOKEN';
    const EOF = 'EOF';
    const EORECORD = 'EORECORD';
    const COMMENT = 'COMMENT';

    public function __construct($type = '', $content = '') {
        $this->type = $type;
        $this->content = $content;
    }
}

class Lexer {
    private $tokens;
    private $current = 0;
    private $ignoreEmptyLines;

    public function __construct($input, $ignoreEmptyLines) {
        $this->tokens = preg_split('/,|\n/', $input);
        $this->ignoreEmptyLines = $ignoreEmptyLines;
        if ($this->ignoreEmptyLines) {
            $this->tokens = array_filter($this->tokens, fn($token) => trim($token) !== '');
        }
    }

    public function nextToken() {
        if ($this->current >= count($this->tokens)) {
            return new Token(Token::EOF, '');
        }
        $token = trim($this->tokens[$this->current++]);
        if ($token === '') {
            return new Token(Token::EORECORD, '');
        }
        return new Token(Token::TOKEN, $token);
    }
}

function runTest($input) {
    $lexer = new Lexer($input, true);
    while ($token = $lexer->nextToken()) {
        if ($token->type === Token::EOF) break;
        echo "$token->content\n";
    }
}

$input = "abc,def, hijk,  lmnop,   qrst,uv ,wxy   ,z , ,";
runTest($input);
--EXPECT--
