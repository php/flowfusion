--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/40)
--FILE--
<?php

class QueueUnlockMain {
    public static function main($args) {
        self::unlock($args[1]);
    }

    private static function unlock($dir) {
        if (!is_dir($dir)) {
            fwrite(STDERR, "Path argument must be a queue directory\n");
            exit(1);
        }

        $storeFilePath = $dir . '/queue_metadata_file.bin';

        if (!file_exists($storeFilePath)) {
            fwrite(STDERR, "Metadata file not found, nothing to unlock\n");
            exit(0);
        }

        $store = new TableStore($storeFilePath);
        $queueLock = new TSQueueLock($store, function() { return new BusyTimedPauser(); }, 0);
        $writeLock = new TableStoreWriteLock($store, function() { return new BusyTimedPauser(); }, 0);

        self::forceUnlock($queueLock);
        self::forceUnlock($writeLock);

        echo "Done\n";
    }

    private static function forceUnlock($lock) {
        $reflection = new ReflectionClass($lock);
        $method = $reflection->getMethod('forceUnlock');
        $method->setAccessible(true);
        $method->invoke($lock);
    }
}

class TableStore {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }
}

class TSQueueLock {
    private $store;
    private $pauser;
    private $timeout;

    public function __construct($store, $pauser, $timeout) {
        $this->store = $store;
        $this->pauser = $pauser;
        $this->timeout = $timeout;
    }
}

class TableStoreWriteLock {
    private $store;
    private $pauser;
    private $timeout;

    public function __construct($store, $pauser, $timeout) {
        $this->store = $store;
        $this->pauser = $pauser;
        $this->timeout = $timeout;
    }
}

class BusyTimedPauser {
    // Assume some busy waiting logic
}

QueueUnlockMain::main($argv);
--EXPECT--
