--TEST--
Translated seed (inferredbugs-java: Chronicle-Bytes/12)
--FILE--
<?php

class NativeBytesStore {
    private $memory;
    private $address;
    private $maximumLimit;

    public function __construct($capacity) {
        $this->memory = array_fill(0, $capacity, 0);
        $this->address = 0;
        $this->maximumLimit = $capacity;
    }

    public function writeByte($offset, $value) {
        if ($offset >= $this->maximumLimit) {
            throw new Exception("Buffer Overflow");
        }
        $this->memory[$offset] = $value;
        return $this;
    }

    public function readByte($offset) {
        if ($offset >= $this->maximumLimit) {
            throw new Exception("Buffer Underflow");
        }
        return $this->memory[$offset];
    }

    public function copy($from, $to, $length) {
        if ($from < 0 || $to < 0 || $from + $length > $this->maximumLimit || $to + $length > $this->maximumLimit) {
            throw new Exception("Buffer Overflow/Underflow");
        }
        for ($i = 0; $i < $length; $i++) {
            $this->memory[$to + $i] = $this->memory[$from + $i];
        }
    }

    public function fill($start, $end, $value) {
        if ($start < 0 || $end > $this->maximumLimit || $start >= $end) {
            throw new Exception("Invalid range");
        }
        for ($i = $start; $i < $end; $i++) {
            $this->memory[$i] = $value;
        }
    }

    public function getMemory() {
        return $this->memory;
    }
}

$store = new NativeBytesStore(128);
$store->writeByte(0, 1);
$store->writeByte(1, 2);
$store->writeByte(2, 3);
$store->copy(0, 3, 3);
$store->fill(6, 10, 255);
print_r($store->getMemory());
--EXPECT--
