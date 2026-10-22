--TEST--
Translated seed (inferredbugs-java: commons-codec/36)
--FILE--
<?php

$encodedFoo = "MZXW6===";
$decodedBytes = "Hello World";
$encodedBytes = base32_encode($decodedBytes);

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encodedBytes);
rewind($inputStream);

$base32InputStream = new Base32InputStream($inputStream);
$base32InputStream->skip(1);
$decoded = $base32InputStream->readAll();

class Base32InputStream {
    private $inputStream;

    public function __construct($inputStream) {
        $this->inputStream = $inputStream;
    }

    public function skip($n) {
        return fseek($this->inputStream, $n, SEEK_CUR);
    }

    public function readAll() {
        $result = '';
        while(!feof($this->inputStream)) {
            $result .= fread($this->inputStream, 1);
        }
        return $result;
    }
}

function base32_encode($data) {
    return base64_encode($data); // Simplified, would normally be a true Base32 encoding
}
--EXPECT--
