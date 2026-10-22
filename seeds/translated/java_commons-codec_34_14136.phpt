--TEST--
Translated seed (inferredbugs-java: commons-codec/34)
--FILE--
<?php

$encoded_foo = "MZXW6===";
$decoded_foo = "Hello World";

// Simulates skipping bytes in an input stream
class Base32InputStream {
    private $stream;
    private $buffer;
    private $bufferSize;
    private $index;

    public function __construct($stream) {
        $this->stream = $stream;
        $this->buffer = [];
        $this->bufferSize = 0;
        $this->index = 0;
    }

    public function read() {
        if ($this->index < $this->bufferSize) {
            return ord($this->buffer[$this->index++]);
        }
        $data = fread($this->stream, 1);
        if ($data === false || $data === '') {
            return -1;
        }
        $this->buffer[] = $data;
        $this->bufferSize++;
        return ord($data);
    }

    public function available() {
        return $this->bufferSize - $this->index;
    }

    public function skip($n) {
        if ($n < 0) {
            throw new InvalidArgumentException("Negative skip amount");
        }
        $toSkip = min($n, $this->bufferSize - $this->index);
        $this->index += $toSkip;
        return $toSkip;
    }

    public function close() {
        fclose($this->stream);
    }
}

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encoded_foo);
rewind($inputStream);

$b32stream = new Base32InputStream($inputStream);
$b32stream->skip(1);
$decodedBytes = [];
while (($byte = $b32stream->read()) != -1) {
    $decodedBytes[] = $byte;
}
$str = implode(array_map("chr", $decodedBytes));

echo $str === substr($decoded_foo, 1) ? 'Success' : 'Failure';

$b32stream->close();
--EXPECT--
