--TEST--
Translated seed (inferredbugs-java: commons-csv/8)
--FILE--
<?php

class ExtendedBufferedReader {
    const END_OF_STREAM = null;
    const UNDEFINED = -1;
    private $buffer;
    private $lineNumber = 0;
    private $position = 0;

    public function __construct($string) {
        $this->buffer = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function lookAhead() {
        return $this->buffer[$this->position] ?? self::END_OF_STREAM;
    }

    public function read() {
        return $this->buffer[$this->position++] ?? self::END_OF_STREAM;
    }

    public function readAgain() {
        return $this->position > 0 ? $this->buffer[$this->position - 1] : self::END_OF_STREAM;
    }

    public function getLineNumber() {
        return $this->lineNumber;
    }

    public function readLine() {
        $line = '';
        while (isset($this->buffer[$this->position]) && $this->buffer[$this->position] !== "\n") {
            $line .= $this->buffer[$this->position++];
        }
        if ($this->buffer[$this->position] === "\n") {
            $this->lineNumber++;
            $this->position++;
        }
        return $line === '' ? null : $line;
    }
}

$br = new ExtendedBufferedReader("1\n2\r3\n");
echo $br->lookAhead() . PHP_EOL;
echo $br->read() . PHP_EOL;
echo $br->readAgain() . PHP_EOL;
echo $br->read() . PHP_EOL;
echo $br->readAgain() . PHP_EOL;

$br = new ExtendedBufferedReader("foo\n\nhello");
echo $br->readLine() . PHP_EOL;
echo $br->readLine() . PHP_EOL;
echo $br->readLine() . PHP_EOL;

$br = new ExtendedBufferedReader("abcdefg");
$charArray = [];
$charArray[0] = $br->read();
$charArray[1] = $br->read();
echo implode('', $charArray) . PHP_EOL;

$br = new ExtendedBufferedReader("ABCDEF,GHL,,MN");
echo $br->readLine() . PHP_EOL;
echo $br->readLine() . PHP_EOL;

$br = new ExtendedBufferedReader("12345");
while ($br->lookAhead() !== ExtendedBufferedReader::END_OF_STREAM) {
    echo $br->read();
}
--EXPECT--
