--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/12)
--FILE--
<?php

class PrefetchRecordsPublisher {
    private $maxPendingProcessRecordsInput;
    private $maxByteSize;
    private $maxRecordsCount;
    private $maxRecordsPerCall;
    private $recordsQueue;
    private $requestedResponses;
    private $started = false;

    public function __construct($maxPendingProcessRecordsInput, $maxByteSize, $maxRecordsCount, $maxRecordsPerCall) {
        $this->maxPendingProcessRecordsInput = $maxPendingProcessRecordsInput;
        $this->maxByteSize = $maxByteSize;
        $this->maxRecordsCount = $maxRecordsCount;
        $this->maxRecordsPerCall = $maxRecordsPerCall;
        $this->recordsQueue = [];
        $this->requestedResponses = 0;
    }

    public function start($extendedSequenceNumber) {
        if ($this->started) {
            throw new Exception("Already started.");
        }
        $this->started = true;
        // Simulating data fetching
        for ($i = 0; $i < 10; $i++) {
            $this->addArrivedRecordsInput(rand(1, 100));
        }
    }

    private function addArrivedRecordsInput($recordsCount) {
        while (count($this->recordsQueue) >= $this->maxPendingProcessRecordsInput) {
            usleep(100000); // Simulate waiting
        }
        if (count($this->recordsQueue) < $this->maxPendingProcessRecordsInput) {
            $this->recordsQueue[] = $recordsCount;
        }
    }

    public function request($n) {
        $this->requestedResponses += $n;
        $this->drainQueueForRequests();
    }

    private function drainQueueForRequests() {
        while ($this->requestedResponses > 0 && !empty($this->recordsQueue)) {
            $recordsToDeliver = array_shift($this->recordsQueue);
            echo "Delivering records: $recordsToDeliver\n";
            $this->requestedResponses--;
        }
    }

    public function shutdown() {
        $this->started = false;
        $this->recordsQueue = [];
    }
}

$publisher = new PrefetchRecordsPublisher(5, 1024, 10, 2);
$publisher->start(1);
$publisher->request(3);
$publisher->shutdown();
--EXPECT--
