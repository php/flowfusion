--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/19)
--FILE--
<?php

$SIZE_512_KB = 512 * 1024;
$MAX_RECORDS_PER_CALL = 10000;
$MAX_SIZE = 5;
$MAX_RECORDS_COUNT = 15000;
$NEXT_SHARD_ITERATOR = "testNextShardIterator";

class DataFetcher {
    public function getStreamIdentifier() {
        return "testStream";
    }
    public function isShardEndReached() {
        return false;
    }
    public function restartIterator() {}
}

class PrefetchRecordsPublisher {
    private $size;
    private $maxRecordsCount;
    private $queue = [];
    private $dataFetcher;

    public function __construct($size, $maxRecordsCount, $dataFetcher) {
        $this->size = $size;
        $this->maxRecordsCount = $maxRecordsCount;
        $this->dataFetcher = $dataFetcher;
    }

    public function start() {
        for ($i = 0; $i < $this->maxRecordsCount; $i++) {
            if (count($this->queue) < $this->size) {
                $this->queue[] = "record" . $i; // Simulating record addition
            }
        }
    }

    public function getQueueSize() {
        return count($this->queue);
    }
}

$dataFetcher = new DataFetcher();
$publisher = new PrefetchRecordsPublisher($MAX_SIZE, $MAX_RECORDS_COUNT, $dataFetcher);

$publisher->start();

sleep(2); // Simulating time for cache filling

$queueSize = $publisher->getQueueSize();
if ($queueSize >= $MAX_SIZE) {
    echo "Queue size reached: $queueSize\n";
} else {
    echo "Queue size not reached: $queueSize\n";
}
--EXPECT--
