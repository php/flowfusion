--TEST--
Translated seed (inferredbugs-csharp: AsmResolver/1)
--FILE--
<?php

class PeImage {
    private $stream;
    private $filename;

    public static function loadFromAssembly($path) {
        return new PeImage($path);
    }

    private function __construct($file) {
        $this->filename = $file;
        $this->stream = fopen($file, 'rb');
        $this->writeToMemoryStream();
    }

    private function writeToMemoryStream() {
        $this->stream = fopen('php://memory', 'r+');
        $buffer = fread($this->stream, 8192);
        while ($buffer !== false && strlen($buffer) > 0) {
            fwrite($this->stream, $buffer);
            $buffer = fread($this->stream, 8192);
        }
        rewind($this->stream);
    }

    public function write($offset, $value) {
        fseek($this->stream, $offset);
        fwrite($this->stream, $value);
    }

    public function readBytes($offset, $length) {
        fseek($this->stream, $offset);
        return fread($this->stream, $length);
    }

    public function readByte($offset) {
        $bytes = $this->readBytes($offset, 1);
        return ord($bytes);
    }

    public function setOffset($offset) {
        fseek($this->stream, $offset);
    }

    public function getPosition() {
        return ftell($this->stream);
    }

    public function getLength() {
        return strlen(stream_get_contents($this->stream, -1, 0));
    }

    public function dispose() {
        fclose($this->stream);
    }
}

$peImage = PeImage::loadFromAssembly('path/to/executable.exe');
$peImage->write(0, 'A');
$byte = $peImage->readByte(0);
$length = $peImage->getLength();
$peImage->dispose();
--EXPECT--
