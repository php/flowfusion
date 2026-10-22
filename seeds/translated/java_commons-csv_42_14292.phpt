--TEST--
Translated seed (inferredbugs-java: commons-csv/42)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = -1;
    const UNDEFINED = -2;
    private $buffer;
    private $position = 0;
    private $lineNumber = 0;
    
    public function __construct($string) {
        $this->buffer = str_split($string);
    }
    
    public function read() {
        if ($this->position >= count($this->buffer)) return self::END_OF_STREAM;
        return $this->buffer[$this->position++];
    }
    
    public function lookAhead() {
        if ($this->position >= count($this->buffer)) return self::END_OF_STREAM;
        return $this->buffer[$this->position];
    }
    
    public function readAgain() {
        if ($this->position == 0) return self::UNDEFINED;
        return $this->buffer[$this->position - 1];
    }
    
    public function readLine() {
        if ($this->position >= count($this->buffer)) return null;
        $line = '';
        while ($this->position < count($this->buffer) && $this->buffer[$this->position] !== "\n") {
            $line .= $this->buffer[$this->position++];
        }
        if ($this->position < count($this->buffer)) $this->position++; // skip newline
        $this->lineNumber++;
        return $line;
    }
    
    public function getLineNumber() {
        return $this->lineNumber;
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");
echo $br->lookAhead();
echo $br->read();
echo $br->readAgain();
echo $br->getLineNumber();
echo $br->lookAhead();
echo $br->read();
echo $br->getLineNumber();
echo $br->lookAhead();
echo $br->read();
echo $br->readAgain();
echo $br->getLineNumber();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->lookAhead();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->readLine();
echo $br->getLineNumber();
echo $br->read();
echo $br->lookAhead();
--EXPECT--
