--TEST--
Translated seed (inferredbugs-java: commons-csv/5)
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
        if ($this->position >= count($this->buffer)) {
            return self::END_OF_STREAM;
        }
        return $this->buffer[$this->position++];
    }
    
    public function lookAhead() {
        if ($this->position >= count($this->buffer)) {
            return self::END_OF_STREAM;
        }
        return $this->buffer[$this->position];
    }

    public function readLine() {
        $line = '';
        while ($this->position < count($this->buffer)) {
            $char = $this->buffer[$this->position++];
            if ($char === "\n") {
                $this->lineNumber++;
                return $line;
            }
            $line .= $char;
        }
        return $line === '' ? null : $line;
    }
    
    public function getLineNumber() {
        return $this->lineNumber;
    }

    public function skip($n) {
        $skipped = 0;
        while ($skipped < $n && $this->position < count($this->buffer)) {
            $this->position++;
            $skipped++;
        }
        return $skipped;
    }
}

$br = new ExtendedBufferedReader("foo\n\nhello");
echo $br->readLine() . "\n"; // foo
echo $br->getLineNumber() . "\n"; // 1
echo $br->readLine() . "\n"; // ''
echo $br->getLineNumber() . "\n"; // 2
echo $br->readLine() . "\n"; // hello
echo $br->getLineNumber() . "\n"; // 3
echo $br->readLine() . "\n"; // (null)
echo $br->getLineNumber() . "\n"; // 3
--EXPECT--
