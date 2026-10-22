--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/5)
--FILE--
<?php

class NativeBytesStore {
    private $data;
    private $capacity;
    
    public function __construct($capacity) {
        $this->capacity = $capacity;
        $this->data = str_repeat("\0", $capacity);
    }
    
    public function bytesForWrite() {
        return new Bytes($this->data);
    }
    
    public static function nativeStoreWithFixedCapacity($capacity) {
        return new self($capacity);
    }
}

class Bytes {
    private $data;
    private $position;
    private $limit;
    
    public function __construct($data) {
        $this->data = $data;
        $this->position = 0;
        $this->limit = strlen($data);
    }
    
    public function writeLong($offset, $value) {
        $this->data = substr_replace($this->data, pack('P', $value), $offset, 8);
    }
    
    public function readLong($offset) {
        return unpack('P', substr($this->data, $offset, 8))[1];
    }
    
    public function writePosition($position) {
        $this->position = $position;
    }
    
    public function readPosition() {
        return $this->position;
    }
    
    public function readLimit() {
        return $this->limit;
    }
}

function main() {
    $nativeStore = NativeBytesStore::nativeStoreWithFixedCapacity(30);
    $bytes = $nativeStore->bytesForWrite();

    $expected = 12345;
    $offset = 5;

    $bytes->writeLong($offset, $expected);
    $bytes->writePosition($offset + 8);
    $actual = $bytes->readLong($offset);
    
    // Not performing assertions, just executing the script
}

main();
--EXPECT--
