--TEST--
Translated seed (inferredbugs-java: commons-csv/19)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = -1;
    const UNDEFINED = -2;
    private $stream;
    private $buffer;
    private $lineNumber = 0;
    private $pos = 0;
    private $length;

    public function __construct($string) {
        $this->stream = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        $this->length = count($this->stream);
    }

    public function read() {
        if ($this->pos >= $this->length) return self::END_OF_STREAM;
        return $this->stream[$this->pos++];
    }

    public function lookAhead() {
        if ($this->pos >= $this->length) return self::END_OF_STREAM;
        return $this->stream[$this->pos];
    }

    public function readAgain() {
        if ($this->pos == 0) return self::UNDEFINED;
        return $this->stream[$this->pos - 1];
    }

    public function getLineNumber() {
        return $this->lineNumber;
    }

    public function readLine() {
        if ($this->pos >= $this->length) return null;
        $line = '';
        while ($this->pos < $this->length) {
            $char = $this->stream[$this->pos++];
            if ($char === "\n") {
                $this->lineNumber++;
                break;
            }
            $line .= $char;
        }
        return $line === '' ? null : $line;
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");

$br->lookAhead();
$br->read();
$br->readAgain();

$br->getLineNumber();
$br->lookAhead();
$br->readAgain();
$br->read();

$br->getLineNumber();
$br->lookAhead();
$br->readAgain();
$br->read();

$br->lookAhead();
$br->readAgain();
$br->read();

$br->lookAhead();
$br->readAgain();
$br->read();

$br->lookAhead();
$br->readAgain();
$br->read();

$br->lookAhead();
$br->readAgain();
$br->read();
$br->readAgain();
$br->read();
$br->lookAhead();
--EXPECT--
