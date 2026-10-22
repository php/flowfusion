--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/9)
--FILE--
<?php

class VanillaBytes {
    private $data;
    private $writePosition;
    private $readPosition;
    private $writeLimit;
    private $readLimit;

    public function __construct($size) {
        $this->data = str_repeat("\0", $size);
        $this->writePosition = 0;
        $this->readPosition = 0;
        $this->writeLimit = $size;
        $this->readLimit = $size;
    }

    public function write($str, $offset, $length) {
        if ($this->writePosition + $length > $this->writeLimit) {
            throw new OverflowException();
        }
        $this->data = substr_replace($this->data, $str, $this->writePosition + $offset, $length);
        $this->writePosition += $length;
    }

    public function read($length) {
        if ($this->readPosition + $length > $this->readLimit) {
            throw new UnderflowException();
        }
        $result = substr($this->data, $this->readPosition, $length);
        $this->readPosition += $length;
        return $result;
    }

    public function append($str) {
        $length = strlen($str);
        if ($this->writePosition + $length > $this->writeLimit) {
            throw new OverflowException();
        }
        $this->write($str, 0, $length);
    }

    public function byteCheckSum() {
        $checksum = 0;
        for ($i = 0; $i < $this->readLimit; $i++) {
            $checksum += ord($this->data[$i]);
        }
        return $checksum & 0xFF;
    }
}

function main() {
    $bytes = new VanillaBytes(128);
    $bytes->write("Hello, World!", 0, 13);
    echo $bytes->read(13) . PHP_EOL; // Outputs "Hello, World!"
    $bytes->append(" Goodbye!");
    echo $bytes->read(19) . PHP_EOL; // Outputs "Hello, World! Goodbye!"
    echo $bytes->byteCheckSum() . PHP_EOL; // Checksum of the data
}

main();
--EXPECT--
