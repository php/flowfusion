--TEST--
Translated seed (inferredbugs-java: Java-Lang/14)
--FILE--
<?php

class DirectBytes {
    private $data;

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
        return count($this->data) > $offset && $this->data[$offset] === 0;
    }

    public function unlockLong($offset) {
        // Do nothing; for this example, we won't simulate actual locking.
    }
}

$bytes = new DirectBytes(64);
$bytes->writeLong(0, -1);
$lockAttempt1 = !$bytes->tryLockLong(0);
$bytes->writeLong(0, 0);
$lockAttempt2 = $bytes->tryLockLong(0);
$val1 = $bytes->readLong(0);
$lockAttempt3 = $bytes->tryLockLong(0);
$bytes->unlockLong(0);
$bytes->unlockLong(0);
$lockAttempt4 = $bytes->readLong(0);

echo ($lockAttempt1 ? 'Failed to lock on -1' : 'Locked on -1') . PHP_EOL;
echo ($lockAttempt2 ? 'Locked on 0' : 'Failed to lock on 0') . PHP_EOL;
echo "Read Value: $val1" . PHP_EOL;
echo "Final Read Value: " . ($lockAttempt4 === 0 ? '0' : $lockAttempt4) . PHP_EOL;
--EXPECT--
