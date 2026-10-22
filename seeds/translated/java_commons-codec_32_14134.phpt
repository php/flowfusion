--TEST--
Translated seed (inferredbugs-java: commons-codec/32)
--FILE--
<?php

$encodedFoo = "MZXW6===";
$decodedString = "Hello World";

class Base32InputStream {
    private $inputStream;
    private $buffer = '';
    private $offset = 0;

    public function __construct($inputStream) {
        $this->inputStream = $inputStream;
    }

    public function read() {
        if ($this->offset < strlen($this->buffer)) {
            return ord($this->buffer[$this->offset++]);
        }
        $this->buffer = fread($this->inputStream, 1024);
        $this->offset = 0;
        if ($this->buffer === false || $this->buffer === '') {
            return -1;
        }
        return ord($this->buffer[$this->offset++]);
    }

    public function skip($n) {
        if ($n < 0) {
            throw new InvalidArgumentException();
        }
        $skipped = 0;
        while ($skipped < $n) {
            if ($this->read() == -1) {
                break;
            }
            $skipped++;
        }
        return $skipped;
    }
}

$inputStream = fopen('php://temp', 'r+');
fwrite($inputStream, $encodedFoo);
rewind($inputStream);

$b32stream = new Base32InputStream($inputStream);
$b32stream->skip(1);
$decodedBytes = [];
for ($i = 0; $i < strlen($decodedString) - 1; $i++) {
    $decodedBytes[] = $b32stream->read();
}

fclose($inputStream);
--EXPECT--
