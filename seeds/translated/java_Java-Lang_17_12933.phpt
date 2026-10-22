--TEST--
Translated seed (inferredbugs-java: Java-Lang/17)
--FILE--
<?php

class DirectBytes {
    private $data;
    private $locked = false;

    public function __construct($size) {
        $this->data = array_fill(0, $size, 0);
    }

    public function writeLong($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function readLong($offset) {
        return $this->data[$offset];
    }

    public function tryLockLong($offset) {
        if (!$this->locked) {
            $this->locked = true;
            return true;
        }
        return false;
    }

    public function unlockLong($offset) {
        if ($this->locked) {
            $this->locked = false;
        } else {
            throw new Exception("IllegalMonitorStateException");
        }
    }
}

$bytes = new DirectBytes(64);
$bytes->writeLong(0, -1);
if (!$bytes->tryLockLong(0)) {
    echo "Lock failed as expected.\n";
}
$bytes->writeLong(0, 0);
if ($bytes->tryLockLong(0)) {
    echo "Lock acquired successfully.\n";
}
$val1 = $bytes->readLong(0);
if ($bytes->tryLockLong(0)) {
    $bytes->unlockLong(0);
}
if ($val1 === $bytes->readLong(0)) {
    echo "Value remains the same after unlock.\n";
}
$bytes->unlockLong(0);
if ($bytes->readLong(0) === 0) {
    echo "Value correctly set to 0 after unlock.\n";
}
try {
    $bytes->unlockLong(0);
} catch (Exception $e) {
    echo "Caught expected exception: " . $e->getMessage() . "\n";
}
--EXPECT--
