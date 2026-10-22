--TEST--
Translated seed (inferredbugs-java: Java-Lang/2)
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
        $this->data = str_repeat("\0", $size);
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
        $this->data[$offset] = pack('N', $value);
    }

    public function readInt($offset) {
        return unpack('N', substr($this->data, $offset, 4))[1];
    }

    public function busyLockInt($offset) {}
    public function unlockInt($offset) {}
}

$start = microtime(true);
$store1 = DirectStore::allocate(1 << 12);
$lockCount = 20 * 1000000;

$t = new Thread(function() use ($store1, $lockCount) {
    $id = intval(Thread::getCurrentThreadId());
    $slice1 = $store1->createSlice();
    for ($i = 0; $i < $lockCount; $i++) {
        $slice1->busyLockInt(0);
        $toggle1 = $slice1->readInt(4);
        if ($toggle1 == 1) {
            $slice1->writeInt(4, 0);
        } else {
            $i--;
        }
        $slice1->unlockInt(0);
    }
});
$t->start();

$id = intval(Thread::getCurrentThreadId());
$slice1 = $store1->createSlice();
for ($i = 0; $i < $lockCount; $i++) {
    $slice1->busyLockInt(0);
    $toggle1 = $slice1->readInt(4);
    if ($toggle1 == 0) {
        $slice1->writeInt(4, 1);
    } else {
        $i--;
    }
    $slice1->unlockInt(0);
}

$store1->free();
$time = microtime(true) - $start;
printf("Contended lock rate was %,d per second\n", (int) ($lockCount * 2 * 1e9 / $time));
--EXPECT--
