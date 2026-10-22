--TEST--
Translated seed (inferredbugs-java: commons-codec/31)
--FILE--
<?php

$ENCODED_FOO = "MZXW6===";
$STRING_FIXTURE = "Hello World";

class Base32InputStream {
    private $stream;
    private $buffer;
    private $position = 0;

    public function __construct($stream) {
        $this->stream = $stream;
        $this->buffer = stream_get_contents($stream);
    }

    public function read() {
        return $this->position < strlen($this->buffer) ? ord($this->buffer[$this->position++]) : -1;
    }

    public function skip($n) {
        $this->position += $n;
        if ($this->position > strlen($this->buffer)) {
            $this->position = strlen($this->buffer);
        }
        return $this->position;
    }

    public function available() {
        return strlen($this->buffer) - $this->position;
    }
}

$bos = fopen('php://temp', 'r+');
fwrite($bos, $STRING_FIXTURE);
rewind($bos);

$ins = new Base32InputStream($bos);
$ins->skip(1);
$decodedBytes = '';
while (($byte = $ins->read()) !== -1) {
    $decodedBytes .= chr($byte);
}
echo $decodedBytes; // should print "ello World"
--EXPECT--
