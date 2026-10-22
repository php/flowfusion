--TEST--
Translated seed (inferredbugs-java: Java-Lang/15)
--FILE--
<?php

class DirectBytes {
    private $data;
    private $locked = false;

    public function __construct($size) {
        $this->data = array_fill(0, $size, 0);
    }

    public function writeLong($index, $value) {
        $this->data[$index] = $value;
    }

    public function readLong($index) {
        return $this->data[$index];
    }

    public function tryLockLong($index) {
        if ($this->locked) {
            return false;
        }
        $this->locked = true;
        return true;
    }

    public function unlockLong($index) {
        if (!$this->locked) {
            throw new Exception("IllegalMonitorStateException");
        }
        $this->locked = false;
    }
}

function testSimpleLock() {
    $bytes = new DirectBytes(64);
    $bytes->writeLong(0, -1);
    if ($bytes->tryLockLong(0) === false) {
        $bytes->writeLong(0, 0);
        if ($bytes->tryLockLong(0)) {
            $val1 = $bytes->readLong(0);
            if ($bytes->tryLockLong(0)) {
                $bytes->unlockLong(0);
                if ($val1 !== $bytes->readLong(0)) {
                    throw new Exception("Values do not match");
                }
                $bytes->unlockLong(0);
                if (0 !== $bytes->readLong(0)) {
                    throw new Exception("Value should be 0");
                }
                try {
                    $bytes->unlockLong(0);
                    throw new Exception("Expected exception not thrown");
                } catch (Exception $e) {
                    // expected
                }
            }
        }
    }
}

testSimpleLock();
--EXPECT--
