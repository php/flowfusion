--TEST--
Translated seed (inferredbugs-java: Java-Lang/18)
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
        unset($this->data);
    }
}

class DirectBytes {
    private $data;

    public function __construct(&$data) {
        $this->data = &$data;
    }

    public function writeLong($index, $value) {
        $this->data[$index] = $value;
    }

    public function readLong($index) {
        return $this->data[$index];
    }

    public function tryLockLong($index) {
        return false;
    }

    public function unlockLong($index) {}

    public function writeInt($index, $value) {
        $this->data[$index] = $value;
    }

    public function readInt($index) {
        return $this->data[$index];
    }

    public function tryLockInt($index) {
        return false;
    }

    public function unlockInt($index) {}
}

function manyToggles($store1, $lockCount, $from, $to) {
    $slice1 = $store1->createSlice();
    for ($i = 0; $i < $lockCount; $i += 64) {
        for ($j = 0; $j < 64; $j++) {
            $toggle1 = $slice1->readInt(4);
            if ($toggle1 == $from) {
                $slice1->writeInt(4, $to);
            } else {
                $i--;
            }
            $slice1->unlockInt(0);
            usleep(1); // small delay
        }
    }
}

$size = 4096;
$store1 = DirectStore::allocate($size);
$lockCount = 10000;

manyToggles($store1, $lockCount, 0, 1);

$store1->free();
--EXPECT--
