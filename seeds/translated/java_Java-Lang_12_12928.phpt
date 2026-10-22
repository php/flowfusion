--TEST--
Translated seed (inferredbugs-java: Java-Lang/12)
--FILE--
<?php

class DirectStore {
    private $size;
    private $data;

    public static function allocate($size) {
        return new self($size);
    }

    private function __construct($size) {
        $this->size = $size;
        $this->data = array_fill(0, $size, 0);
    }

    public function createSlice() {
        return new DirectBytes($this->data);
    }
    
    public function free() {
        $this->data = null;
    }

    public function size() {
        return $this->size;
    }
}

class DirectBytes {
    private $data;

    public function __construct(&$data) {
        $this->data = &$data;
    }

    public function writeLong($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function readLong($offset) {
        return $this->data[$offset];
    }

    public function tryLockLong($offset) {
        return true;
    }

    public function unlockLong($offset) {}

    public function positionAndSize($position, $size) {}
}

$store = DirectStore::allocate(4096);
$slice = $store->createSlice();
$slice->writeLong(0, -1);
$slice->tryLockLong(0);
$slice->writeLong(0, 0);
$slice->tryLockLong(0);
$val1 = $slice->readLong(0);
$slice->tryLockLong(0);
$slice->unlockLong(0);
$slice->unlockLong(0);
$slice->readLong(0);
$slice->unlockLong(0);
$store->free();
--EXPECT--
