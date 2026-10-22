--TEST--
Translated seed (inferredbugs-java: Java-Thread-Affinity/6)
--FILE--
<?php
define('AFFINITY_RESERVED', 'affinity.reserved');

$processors = shell_exec("nproc");
$baseAffinity = 0b111; // Simulated base affinity
$reservedAffinity = getReservedAffinity();

$locks = array();
for ($i = 0; $i < $processors; $i++) {
    $locks[$i] = new AffinityLock($i, (($baseAffinity >> $i) & 1) != 0, (($reservedAffinity >> $i) & 1) != 0);
}

function getReservedAffinity() {
    return isset($_SERVER[AFFINITY_RESERVED]) ? hexdec(trim($_SERVER[AFFINITY_RESERVED])) : ((1 << 3) - 1) ^ 7; 
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

    public function assignCurrentThread($bind) {
        $this->assignedThread = getmypid();
        if ($bind) $this->bind();
    }

    public function bind() {
        if ($this->bound) throw new Exception("CPU " . $this->id . " already bound to process " . $this->assignedThread);
        $this->bound = true;
        $this->assignedThread = getmypid();
    }

    public function canReserve() {
        return $this->reserved && ($this->assignedThread === null || posix_kill($this->assignedThread, 0) === false);
    }
}

$lock = $locks[0];
$lock->assignCurrentThread(true);
?>
--EXPECT--
