--TEST--
Translated seed (inferredbugs-java: Java-Thread-Affinity/2)
--FILE--
<?php
define('AFFINITY_RESERVED', 'affinity.reserved');
$baseAffinity = getAffinity();
$processors = shell_exec("nproc");
$reservedAffinity = getReservedAffinity();

$locks = [];
for ($i = 0; $i < $processors; $i++) {
    $locks[$i] = new AffinityLock($i, (($baseAffinity >> $i) & 1) !== 0, (($reservedAffinity >> $i) & 1) !== 0);
}

function getAffinity() {
    return 0b111; // Example bitmask for 3 processors
}

function getReservedAffinity() {
    $reservedAffinity = getenv(AFFINITY_RESERVED);
    return empty($reservedAffinity) ? ((1 << $GLOBALS['processors']) - 1) ^ $GLOBALS['baseAffinity'] : hexdec($reservedAffinity);
}

class AffinityLock {
    private $id;
    private $base;
    private $reserved;
    private $bound = false;
    private $assignedThread;

    public function __construct($id, $base, $reserved) {
        $this->id = $id;
        $this->base = $base;
        $this->reserved = $reserved;
    }

    private function assignCurrentThread($bind) {
        $this->assignedThread = getmypid();
        if ($bind) $this->bind();
    }

    public function bind() {
        if ($this->bound) throw new Exception("Already bound to " . $this->assignedThread);
        $this->bound = true;
        $this->assignedThread = getmypid();
        // Simulated affinity setting
    }

    public function acquireLock() {
        $this->assignCurrentThread(true);
    }
}

foreach ($locks as $lock) {
    $lock->acquireLock();
}
?>
--EXPECT--
