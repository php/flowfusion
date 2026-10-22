--TEST--
Translated seed (inferredbugs-java: Chronicle-Queue/39)
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

        $storeFilePath = $dir . '/queue_metadata_file'; // Simulating QUEUE_METADATA_FILE

        if (!file_exists($storeFilePath)) {
            fwrite(STDERR, "Metadata file not found, nothing to unlock\n");
            exit(0);
        }

        $store = new TableStore($storeFilePath);
        $queueLock = new TSQueueLock($store);
        $writeLock = new TableStoreWriteLock($store);

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
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }
}

class TSQueueLock {
    private $store;

    public function __construct($store) {
        $this->store = $store;
    }

    public function forceUnlock() {
        // Simulate unlocking logic
    }
}

class TableStoreWriteLock {
    private $store;

    public function __construct($store) {
        $this->store = $store;
    }

    public function forceUnlock() {
        // Simulate unlocking logic
    }
}

QueueUnlockMain::main($argv);
--EXPECT--
