--TEST--
Translated seed (inferredbugs-java: Java-Lang/4)
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
        $this->data = array_fill(0, $size / 4, 0); // assuming 4-byte integers
    }

    public function createSlice() {
        return new DirectBytes($this->data);
    }

    public function free() {
        $this->data = null;
    }
}

class DirectBytes {
    private $data;

    public function __construct(&$data) {
        $this->data = &$data;
    }

    public function writeInt($offset, $value) {
        $this->data[$offset / 4] = $value;
    }

    public function readInt($offset) {
        return $this->data[$offset / 4];
    }

    public function busyLockInt($offset) {
        // Simulate busy lock (no-op in this case)
    }

    public function unlockInt($offset) {
        // Simulate unlock (no-op in this case)
    }
}

function manyToggles($store, $lockCount, $from, $to) {
    $slice = $store->createSlice();
    for ($i = 0; $i < $lockCount; $i++) {
        $slice->busyLockInt(0);
        $toggle = $slice->readInt(4);
        if ($toggle == $from) {
            $slice->writeInt(4, $to);
        } else {
            $i--;
        }
        $slice->unlockInt(0);
        usleep(1); // small delay
    }
}

$store1 = DirectStore::allocate(1 << 12);
$lockCount = 40 * 1000 * 1000;

$thread = new Thread(function() use ($store1, $lockCount) {
    manyToggles($store1, $lockCount, 1, 0);
});

$thread->start();
manyToggles($store1, $lockCount, 0, 1);
$store1->free();
--EXPECT--
