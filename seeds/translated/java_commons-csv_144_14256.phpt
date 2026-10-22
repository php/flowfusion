--TEST--
Translated seed (inferredbugs-java: commons-csv/144)
--FILE--
<?php

class ExtendedBufferedReader {
    private $stream;
    private $currentLineNumber = 0;
    private $lastChar = null;

    public function __construct($string) {
        $this->stream = fopen('php://temp', 'r+');
        fwrite($this->stream, $string);
        rewind($this->stream);
    }

    public function read() {
        $char = fgetc($this->stream);
        if ($char === false) {
            return -1; // END_OF_STREAM
        }
        if ($char === "\n") {
            $this->currentLineNumber++;
        }
        $this->lastChar = $char;
        return $char;
    }

    public function lookAhead() {
        $pos = ftell($this->stream);
        $char = $this->read();
        fseek($this->stream, $pos);
        return $char;
    }

    public function getLastChar() {
        return $this->lastChar;
    }

    public function getCurrentLineNumber() {
        return $this->currentLineNumber;
    }

    public function readLine() {
        $line = fgets($this->stream);
        if ($line === false) {
            return null;
        }
        if (trim($line) === "") {
            $this->currentLineNumber++;
        }
        return rtrim($line);
    }

    public function readBuff($buffer, $offset, $length) {
        return fread($this->stream, $length);
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");
while (($char = $br->read()) != -1) {
    // Process each character
}
$br = new ExtendedBufferedReader("abcdefg");
$res = array_fill(0, 5, null);
$br->readBuff($res, 0, 3);
$br->readBuff($res, 4, 1);
$br = new ExtendedBufferedReader("foo\n\nhello");
while ($br->readLine() !== null) {
    // Consume all lines
}
$br = new ExtendedBufferedReader("foo\rbaar\r\nfoo");
while ($br->readLine() !== null) {
    // Consume all lines
}
--EXPECT--
