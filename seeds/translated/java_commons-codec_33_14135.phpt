--TEST--
Translated seed (inferredbugs-java: commons-codec/33)
--FILE--
<?php

class Base32InputStream {
    private $stream;
    private $buffer = '';
    private $position = 0;

    public function __construct($stream) {
        $this->stream = $stream;
    }

    public function read() {
        if ($this->position >= strlen($this->buffer)) {
            $this->buffer = fread($this->stream, 1);
            $this->position = 0;
        }
        if ($this->buffer === false || $this->buffer === '') {
            return -1;
        }
        return ord($this->buffer[$this->position++]);
    }

    public function skip($n) {
        if ($n < 0) {
            throw new InvalidArgumentException();
        }
        $this->position += $n;
        if ($this->position > strlen($this->buffer)) {
            $this->position = strlen($this->buffer);
        }
        return $this->position;
    }
}

$encodedFoo = "MZXW6===";
$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encodedFoo);
rewind($inputStream);

$b32stream = new Base32InputStream($inputStream);
$b32stream->skip(1);
$result = '';

while (($byte = $b32stream->read()) !== -1) {
    $result .= chr($byte);
}

echo $result;
--EXPECT--
