--TEST--
Translated seed (inferredbugs-java: commons-codec/35)
--FILE--
<?php

$encoded_foo = "MZXW6===";
$crlf = [ "\r", "\n" ];
$lf = [ "\n" ];
$string_fixture = "Hello World";

class Base32InputStream {
    private $inputStream;
    private $buffer = '';
    private $eof = false;

    public function __construct($stream) {
        $this->inputStream = $stream;
    }

    public function read() {
        if ($this->eof) return -1;
        if ($this->buffer === '') {
            $data = fread($this->inputStream, 1);
            if ($data === false || $data === '') {
                $this->eof = true;
                return -1;
            }
            $this->buffer = $data;
        }
        $char = $this->buffer[0];
        $this->buffer = '';
        return ord($char);
    }

    public function skip($n) {
        if ($n < 0) throw new InvalidArgumentException();
        $skipped = 0;
        while ($skipped < $n && !$this->eof) {
            $this->read();
            $skipped++;
        }
        return $skipped;
    }

    public function available() {
        return $this->eof ? 0 : 1;
    }

    public function close() {
        fclose($this->inputStream);
    }
}

$inputStream = fopen('php://temp', 'r+');
fwrite($inputStream, $encoded_foo);
rewind($inputStream);
$b32stream = new Base32InputStream($inputStream);
$b32stream->skip(1);
$decodedBytes = [];
while (($byte = $b32stream->read()) !== -1) {
    $decodedBytes[] = chr($byte);
}
$str = implode('', $decodedBytes);

$expected = substr($string_fixture, 1);
if ($str !== $expected) {
    throw new Exception("Decoded string does not match expected value.");
}

$b32stream->close();
--EXPECT--
