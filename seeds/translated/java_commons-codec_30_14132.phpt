--TEST--
Translated seed (inferredbugs-java: commons-codec/30)
--FILE--
<?php

$encoded_foo = "MZXW6===";
$decoded_foo = "Hello World";
$encoded_bytes = unpack("C*", $encoded_foo);
$bos = fopen('php://temp', 'r+');
$base32os = new Base32OutputStream($bos);
$base32os->write(utf8_encode($decoded_foo));
$base32os->close();
rewind($bos);
$bis = fread($bos, 1024);
$ins = new Base32InputStream($bis);
$ins->skip(1);
$decoded_bytes = streamToBytes($ins, 64);
$str = utf8_decode($decoded_bytes);
echo $str === substr($decoded_foo, 1) ? 'Test Passed' : 'Test Failed';

function streamToBytes($stream, $length) {
    $bytes = '';
    while (($byte = $stream->read()) !== false) {
        $bytes .= chr($byte);
    }
    return $bytes;
}

class Base32InputStream {
    private $stream;
    private $buffer = '';
    private $position = 0;

    public function __construct($stream) {
        $this->stream = $stream;
    }

    public function read() {
        if ($this->position < strlen($this->buffer)) {
            return ord($this->buffer[$this->position++]);
        }
        $this->buffer = fread($this->stream, 1);
        if ($this->buffer === false || $this->buffer === '') {
            return false;
        }
        return ord($this->buffer[0]);
    }

    public function skip($n) {
        if ($n < 0) {
            throw new InvalidArgumentException();
        }
        $skipped = 0;
        while ($skipped < $n && $this->read() !== false) {
            $skipped++;
        }
        return $skipped;
    }
}

class Base32OutputStream {
    private $stream;

    public function __construct($stream) {
        $this->stream = $stream;
    }

    public function write($data) {
        fwrite($this->stream, $data);
    }

    public function close() {
        fclose($this->stream);
    }
}
--EXPECT--
