--TEST--
Translated seed (inferredbugs-java: commons-codec/13)
--FILE--
<?php

$encodedFoo = "MZXW6===";
$stringFixture = "Hello World";

class Base32InputStream {
    private $stream;
    private $isEncoding;
    private $chunkSize;
    private $separator;

    public function __construct($stream, $isEncoding = false, $chunkSize = 0, $separator = null) {
        $this->stream = $stream;
        $this->isEncoding = $isEncoding;
        $this->chunkSize = $chunkSize;
        $this->separator = $separator;
    }

    public function read() {
        return $this->stream->read(1);
    }

    public function skip($n) {
        return $this->stream->skip($n);
    }
}

class ByteArrayInputStream {
    private $data;
    private $position = 0;

    public function __construct($data) {
        $this->data = $data;
    }

    public function read($length) {
        if ($this->position >= strlen($this->data)) return -1;
        $byte = substr($this->data, $this->position, 1);
        $this->position++;
        return $byte;
    }

    public function skip($n) {
        $this->position += $n;
        return $n;
    }
}

$bos = new ByteArrayInputStream("");
$base32os = new Base32InputStream(new ByteArrayInputStream($stringFixture));

$ins = new Base32InputStream(new ByteArrayInputStream($bos->data));

$ins->skip(1);
$decodedBytes = [];
while (($byte = $ins->read()) !== -1) {
    $decodedBytes[] = $byte;
}
$str = implode('', $decodedBytes);

echo ($str === substr($stringFixture, 1)) ? "Pass" : "Fail";
--EXPECT--
