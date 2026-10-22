--TEST--
Translated seed (inferredbugs-java: commons-csv/18)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = -1;
    const UNDEFINED = -2;
    private $stream;
    private $buffer;
    private $lineNumber = 0;

    public function __construct($input) {
        $this->stream = preg_split('//u', $input, -1, PREG_SPLIT_NO_EMPTY);
        $this->buffer = [];
    }

    public function read() {
        return empty($this->stream) ? self::END_OF_STREAM : array_shift($this->stream);
    }

    public function lookAhead() {
        return empty($this->stream) ? self::END_OF_STREAM : $this->stream[0];
    }

    public function readAgain() {
        return empty($this->buffer) ? ($this->buffer[] = $this->read()) : array_pop($this->buffer);
    }

    public function getLineNumber() {
        return $this->lineNumber;
    }

    public function readLine() {
        if (empty($this->stream)) return null;
        $line = '';
        while (true) {
            $char = $this->read();
            if ($char === "\n" || $char === "\r" || $char === self::END_OF_STREAM) {
                if ($char === "\r" && $this->lookAhead() === "\n") $this->read();
                $this->lineNumber++;
                return $line;
            }
            $line .= $char;
        }
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");
$br->read();
$br->lookAhead();
$br->readAgain();
$br->read();
$br->readAgain();
$br->readLine();
$br->readAgain();
$br->readLine();

$br = new ExtendedBufferedReader("abcdefg");
$res = [];
$br->read();
$res[] = $br->read();
$br->read();
$res[] = $br->read();
$br->read();
$res[] = $br->read();
$br->read();

$br = new ExtendedBufferedReader("\n");
$br->readLine();
$br->readLine();
$br = new ExtendedBufferedReader("foo\n\nhello");
$br->readLine();
$br->readLine();
$br->readLine();
$br->readLine();
$br->read();
$br->read();
$br->read();
$br->readLine();
$br->readLine();

$br = new ExtendedBufferedReader("foo\rbaar\r\nfoo");
$br->readLine();
$br->readLine();
$br->readLine();
$br->readLine();
--EXPECT--
