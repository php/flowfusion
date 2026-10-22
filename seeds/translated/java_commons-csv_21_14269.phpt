--TEST--
Translated seed (inferredbugs-java: commons-csv/21)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = -1;
    const UNDEFINED = 0;
    private $stream;
    private $lineNumber = 0;
    private $buffer = '';
    private $position = 0;

    public function __construct($str) {
        $this->stream = fopen('php://temp', 'r+');
        fwrite($this->stream, $str);
        rewind($this->stream);
    }

    public function read() {
        if ($this->position >= strlen($this->buffer)) {
            $this->buffer = fgets($this->stream);
            if ($this->buffer === false) return self::END_OF_STREAM;
            $this->position = 0;
            $this->lineNumber++;
        }
        return $this->position < strlen($this->buffer) ? $this->buffer[$this->position++] : self::END_OF_STREAM;
    }

    public function lookAhead() {
        if ($this->position >= strlen($this->buffer)) {
            $this->buffer = fgets($this->stream);
            if ($this->buffer === false) return self::END_OF_STREAM;
            $this->position = 0;
        }
        return $this->position < strlen($this->buffer) ? $this->buffer[$this->position] : self::END_OF_STREAM;
    }

    public function readLine() {
        return fgets($this->stream);
    }

    public function getLineNumber() {
        return $this->lineNumber;
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");
echo $br->lookAhead();
echo $br->read();
echo $br->getLineNumber();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->lookAhead();
echo $br->read();
echo $br->lookAhead();
echo $br->read();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->read();
echo $br->lookAhead();
echo $br->read();
echo $br->lookAhead();
echo $br->read();
echo $br->lookAhead();
--EXPECT--
