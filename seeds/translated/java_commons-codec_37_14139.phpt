--TEST--
Translated seed (inferredbugs-java: commons-codec/37)
--FILE--
<?php

$ENCODED_B64 = "AAAA////";
$STRING_FIXTURE = "Hello World";

class Base64InputStream {
    private $stream;
    private $buffer;
    
    public function __construct($stream) {
        $this->stream = $stream;
        $this->buffer = '';
    }
    
    public function read() {
        if ($this->buffer === '') {
            $this->buffer = base64_decode(fread($this->stream, 4));
        }
        if ($this->buffer === false) return -1;
        $char = substr($this->buffer, 0, 1);
        $this->buffer = substr($this->buffer, 1);
        return $char === '' ? -1 : ord($char);
    }
    
    public function close() {
        fclose($this->stream);
    }
    
    public function skip($n) {
        return fread($this->stream, $n);
    }
}

$ins = fopen('data://text/plain;base64,' . base64_encode($ENCODED_B64), 'rb');
$b64stream = new Base64InputStream($ins);
$b64stream->skip(1);
$decodedBytes = [];
for ($i = 0; $i < strlen($STRING_FIXTURE) - 1; $i++) {
    $decodedBytes[] = $b64stream->read();
}
$b64stream->close();
--EXPECT--
