--TEST--
Translated seed (inferredbugs-java: bookkeeper/6)
--FILE--
<?php

class BenchThroughputLatency {
    private $counter = 0;
    private $latencies = [];
    private $sendLimit = 2000000;
    private $startTime;
    private $numberOfSent = 0;
    private $throttle = 10000;
    private $sem;

    public function __construct() {
        $this->sem = new Semaphore($this->throttle);
    }

    public function setSendLimit($limit) {
        $this->sendLimit = $limit;
    }

    public function run() {
        $this->startTime = microtime(true);
        $sent = 0;

        while ($sent < $this->sendLimit) {
            if ($this->sem->acquire()) {
                $this->asyncAddEntry(random_int(0, 999), $sent);
                $sent++;
            }
        }

        while ($this->counter > 0) {
            usleep(100000); // wait for the operations to complete
        }

        $duration = (microtime(true) - $this->startTime) * 1000;
        echo "Finished processing in ms: $duration\n";
    }

    public function asyncAddEntry($entryId, $contextId) {
        // Simulate latency
        usleep(random_int(1000, 5000));
        $this->latencies[$contextId] = microtime(true) - $this->startTime;

        $this->counter++;
        $this->sem->release();
    }

    public function getLatencyStats() {
        sort($this->latencies);
        $count = count($this->latencies);
        return [
            '99th' => $this->latencies[(int)($count * 0.99)],
            '95th' => $this->latencies[(int)($count * 0.95)],
        ];
    }
}

class Semaphore {
    private $limit;
    private $current = 0;

    public function __construct($limit) {
        $this->limit = $limit;
    }

    public function acquire() {
        while ($this->current >= $this->limit) {
            usleep(100000); // wait
        }
        $this->current++;
        return true;
    }

    public function release() {
        $this->current--;
    }
}

$bench = new BenchThroughputLatency();
$bench->setSendLimit(20000);
$bench->run();
echo json_encode($bench->getLatencyStats()) . "\n";
--EXPECT--
