--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/7)
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

    public function readLong($offset) {
        return unpack("P", substr($this->buffer, $offset, 8))[1];
    }

    public function writeLong($offset, $value) {
        $this->buffer = substr_replace($this->buffer, pack("P", $value), $offset, 8);
    }

    public function limit($newLimit) {
        $this->limit = $newLimit;
    }

    public function bytes() {
        return new Bytes($this->limit - $this->position);
    }

    public function start() {
        return $this->position;
    }

    public function capacity() {
        return strlen($this->buffer);
    }

    public function readPosition($position) {
        $this->position = $position;
    }

    public function readLimit($limit) {
        $this->limit = $limit;
    }

    public function __toString() {
        return $this->buffer;
    }
}

function testBytes() {
    $bytes = new Bytes(30);
    $expected = 12345;
    $offset = 5;

    $bytes->writeLong($offset, $expected);
    $bytes->readPosition($offset + 8);

    if ($expected !== $bytes->readLong($offset)) {
        throw new Exception("Test failed");
    }
}

testBytes();
--EXPECT--
