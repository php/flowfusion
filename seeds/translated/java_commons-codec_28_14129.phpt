--TEST--
Translated seed (inferredbugs-java: commons-codec/28)
--FILE--
<?php

class Base32InputStream {
    private $inputStream;
    private $buffer = '';
    private $position = 0;

    public function __construct($inputStream) {
        $this->inputStream = $inputStream;
        $this->buffer = stream_get_contents($inputStream);
    }

    public function read() {
        if ($this->position >= strlen($this->buffer)) {
            return -1;
        }
        return ord($this->buffer[$this->position++]);
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

    public function close() {
        fclose($this->inputStream);
    }
}

$encodedFoo = "MZXW6===";
$inputStream = fopen('data://text/plain;base64,' . base64_encode($encodedFoo), 'r');
$b32stream = new Base32InputStream($inputStream);
$b32stream->skip(1);
$decodedBytes = [];
while (($byte = $b32stream->read()) !== -1) {
    $decodedBytes[] = $byte;
}
$output = implode('', array_map('chr', $decodedBytes));
echo $output; // Output the decoded string
$b32stream->close();
--EXPECT--
