--TEST--
Translated seed (inferredbugs-java: Java-Lang/13)
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
        if ($this->locked) return false;
        $this->locked = true;
        return true;
    }

    public function unlockLong($offset) {
        if (!$this->locked) throw new Exception("IllegalMonitorStateException");
        $this->locked = false;
    }
}

$bytes = new DirectBytes(64);
$bytes->writeLong(0, -1);
$bytes->tryLockLong(0);
$bytes->writeLong(0, 0);
$bytes->tryLockLong(0);
$val1 = $bytes->readLong(0);
$bytes->tryLockLong(0);
$bytes->unlockLong(0);
$bytes->unlockLong(0);
$bytes->unlockLong(0);
try {
    $bytes->unlockLong(0);
} catch (Exception $e) {
    // expected.
}
--EXPECT--
