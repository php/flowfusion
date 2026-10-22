--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/13)
--FILE--
<?php

$SIZE_512_KB = 512 * 1024;
$SIZE_1_MB = 2 * $SIZE_512_KB;
$MAX_RECORDS_PER_CALL = 10000;
$MAX_SIZE = 5;
$MAX_RECORDS_COUNT = 15000;
$IDLE_MILLIS_BETWEEN_CALLS = 0;

class Record {
    public $data;
    public function __construct($data) {
        $this->data = $data;
    }
}

class GetRecordsResponse {
    public $records;
    public function __construct($records) {
        $this->records = $records;
    }
}

class PrefetchRecordsPublisher {
    private $maxSize;
    private $queue;
    public function __construct($maxSize) {
        $this->maxSize = $maxSize;
        $this->queue = [];
    }
    
    public function addRecord($record) {
        if (count($this->queue) < $this->maxSize) {
            $this->queue[] = $record;
        }
    }
    
    public function size() {
        return count($this->queue);
    }
}

$records = [];
$getRecordsCache = new PrefetchRecordsPublisher($MAX_SIZE);

$record = new Record(str_repeat('x', $SIZE_512_KB));
for ($i = 0; $i < 1000; $i++) {
    $getRecordsCache->addRecord($record);
}

usleep(2000000);

if ($getRecordsCache->size() >= $MAX_SIZE) {
    echo "Cache size reached: " . $getRecordsCache->size();
}
--EXPECT--
