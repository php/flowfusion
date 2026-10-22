--TEST--
Translated seed (inferredbugs-java: Java-Thread-Affinity/1)
--FILE--
<?php
class AffinityLock {
    const AFFINITY_RESERVED = 'affinity.reserved';
    const PROCESSORS = 4; // Simulating a fixed number of processors for demonstration
    const BASE_AFFINITY = 0b0001; // Simulated base affinity
    const RESERVED_AFFINITY = 0b1110; // Simulated reserved affinity

    private static $locks = [];
    private static $none;
    private $id;
    private $base;
    private $reserved;
    private $bound = false;
    private $assignedThread;

    public static function init() {
        self::$none = new AffinityLock(-1, false, false);
        for ($i = 0; $i < self::PROCESSORS; $i++) {
            self::$locks[$i] = new AffinityLock($i, (self::BASE_AFFINITY >> $i & 1) !== 0, (self::RESERVED_AFFINITY >> $i & 1) !== 0);
        }
    }

    public static function acquireLock() {
        return self::acquireLockInternal(true);
    }

    private static function acquireLockInternal($bind) {
        for ($i = self::PROCESSORS - 1; $i >= 0; $i--) {
            $al = self::$locks[$i];
            if ($al->canReserve()) {
                $al->assignCurrentThread($bind);
                return $al;
            }
        }
        return self::$none;
    }

    private function __construct($id, $base, $reserved) {
        $this->id = $id;
        $this->base = $base;
        $this->reserved = $reserved;
    }

    private function assignCurrentThread($bind) {
        $this->assignedThread = getmyuid(); // Simulating thread reference with user ID
        if ($bind) $this->bind();
    }

    private function bind() {
        if ($this->bound) throw new Exception("Already bound to thread " . $this->assignedThread);
        $this->bound = true;
        $this->assignedThread = getmyuid();
        // Simulated affinity setting
    }

    private function canReserve() {
        if (!$this->reserved) return false;
        if ($this->assignedThread !== null) return false; // Simulating check for thread state
        return true;
    }
}

AffinityLock::init();
AffinityLock::acquireLock();
--EXPECT--
