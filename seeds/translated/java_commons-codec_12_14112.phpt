--TEST--
Translated seed (inferredbugs-java: commons-codec/12)
--FILE--
<?php

$encodedB64 = "AAAA////";
$stringFixture = "Hello World";

class Base64InputStream {
    private $stream;
    private $buffer;
    private $bufferPosition;
    private $bufferSize;

    public function __construct($stream) {
        $this->stream = $stream;
        $this->buffer = '';
        $this->bufferPosition = 0;
        $this->bufferSize = 0;
    }

    public function read($length = 1) {
        if ($this->bufferSize === 0) {
            $this->buffer = base64_decode(fread($this->stream, 64));
            $this->bufferSize = strlen($this->buffer);
            $this->bufferPosition = 0;
        }
        if ($this->bufferPosition < $this->bufferSize) {
            $bytes = substr($this->buffer, $this->bufferPosition, $length);
            $this->bufferPosition += strlen($bytes);
            return $bytes;
        }
        return false;
    }

    public function skip($n) {
        $this->read($n);
    }

    public function close() {
        fclose($this->stream);
    }
}

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, base64_encode($stringFixture));
rewind($inputStream);

$in = new Base64InputStream($inputStream);
$in->skip(1);

$output = '';
while (($char = $in->read(1)) !== false) {
    $output .= $char;
}

echo $output;
--EXPECT--
