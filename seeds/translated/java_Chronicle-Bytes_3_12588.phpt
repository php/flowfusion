--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/3)
--FILE--
<?php

class Bytes {
    private $buffer;
    private $position;
    private $limit;

    public function __construct($size) {
        $this->buffer = str_repeat("\0", $size);
        $this->position = 0;
        $this->limit = $size;
    }

    public function readByte() {
        return ord($this->buffer[$this->position++]);
    }

    public function writeLong($offset, $value) {
        $this->buffer[$offset] = pack("P", $value);
    }

    public function readLong($offset) {
        return unpack("P", substr($this->buffer, $offset, 8))[1];
    }

    public function limit($limit) {
        $this->limit = $limit;
    }

    public function writePosition($position) {
        $this->position = $position;
    }

    public function readLimit() {
        return $this->limit;
    }

    public function bytesForWrite() {
        return $this;
    }

    public function toHexString() {
        $hex = '';
        for ($i = 0; $i < $this->limit; $i++) {
            $hex .= sprintf("%02X ", ord($this->buffer[$i]));
        }
        return $hex;
    }
}

function main() {
    $bytes = new Bytes(30);
    
    $expected = 12345;
    $offset = 5;

    $bytes->writeLong($offset, $expected);
    $bytes->writePosition($offset + 8);

    $num = $bytes->readLong($offset);
    $hexString = $bytes->toHexString();

    echo "Value: $num\n";
    echo "Hex: $hexString\n";
}

main();
--EXPECT--
