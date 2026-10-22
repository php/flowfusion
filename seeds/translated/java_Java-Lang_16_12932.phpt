--TEST--
Translated seed (inferredbugs-java: Java-Lang/16)
--FILE--
<?php

class DirectBytes {
    private $data;

    public function __construct($size) {
        $this->data = array_fill(0, $size, 0);
    }

    public function writeLong($pos, $value) {
        $this->data[$pos] = $value;
    }

    public function readLong($pos) {
        return $this->data[$pos];
    }

    public function tryLockLong($pos) {
        return $this->data[$pos] !== 0;
    }

    public function unlockLong($pos) {
        if ($this->data[$pos] === 0) {
            throw new Exception("IllegalMonitorStateException");
        }
        $this->data[$pos] = 0;
    }
}

class DirectStore {
    private $size;

    public function __construct($size) {
        $this->size = $size;
    }

    public static function allocate($size) {
        return new self($size);
    }

    public function createSlice() {
        return new DirectBytes($this->size);
    }

    public function free() {
        // Simulate freeing resources
    }

    public function size() {
        return $this->size;
    }
}

$store = DirectStore::allocate(64);
$bytes = $store->createSlice();
$bytes->writeLong(0, -1);
if ($bytes->tryLockLong(0)) {
    $bytes->unlockLong(0);
}
$bytes->writeLong(0, 0);
if (!$bytes->tryLockLong(0)) {
    echo "Lock failed\n";
}
$val1 = $bytes->readLong(0);
$bytes->unlockLong(0);
$bytes->unlockLong(0);

try {
    $bytes->unlockLong(0);
} catch (Exception $e) {
    echo "Expected exception: " . $e->getMessage() . "\n";
}
--EXPECT--
