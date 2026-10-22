--TEST--
Translated seed (inferredbugs-java: commons-csv/1)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = -1;
    const UNDEFINED = -2;

    private $stream;
    private $lineNumber = 0;
    private $buffer = '';
    private $pos = 0;
    
    public function __construct($string) {
        $this->stream = $string;
        $this->buffer = $this->stream;
    }
    
    public function lookAhead() {
        return $this->pos < strlen($this->buffer) ? $this->buffer[$this->pos] : self::END_OF_STREAM;
    }
    
    public function read() {
        if ($this->pos >= strlen($this->buffer)) return self::END_OF_STREAM;
        return $this->buffer[$this->pos++];
    }
    
    public function readLine() {
        if ($this->pos >= strlen($this->buffer)) return null;
        $endPos = strpos($this->buffer, "\n", $this->pos);
        if ($endPos === false) {
            $line = substr($this->buffer, $this->pos);
            $this->pos = strlen($this->buffer);
        } else {
            $line = substr($this->buffer, $this->pos, $endPos - $this->pos);
            $this->pos = $endPos + 1;
        }
        $this->lineNumber++;
        return $line;
    }
    
    public function getLineNumber() {
        return $this->lineNumber - 1;
    }

    public function skip($n) {
        if ($n <= 0) return 0;
        $charsSkipped = min($n, strlen($this->buffer) - $this->pos);
        $this->pos += $charsSkipped;
        return $charsSkipped;
    }
}

$br = new ExtendedBufferedReader("foo\n\nhello");
echo $br->readLine() . "\n"; // foo
echo $br->getLineNumber() . "\n"; // 0
echo $br->readLine() . "\n"; // (empty)
echo $br->getLineNumber() . "\n"; // 1
echo $br->readLine() . "\n"; // hello
echo $br->getLineNumber() . "\n"; // 2
echo $br->readLine() . "\n"; // (null)
echo $br->getLineNumber() . "\n"; // 2
--EXPECT--
