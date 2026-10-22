--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/6)
--FILE--
<?php

$size512KB = 512 * 1024;
$size1MB = 2 * $size512KB;
$maxRecordsPerCall = 10000;
$maxSize = 5;
$maxRecordsCount = 15000;

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

class GetRecordsRetrievalStrategy {
    public function getRecords($count) {
        if ($count > 0) {
            return new GetRecordsResponse(array_fill(0, $count, new Record(str_repeat('x', 512))));
        }
        throw new Exception("No records");
    }
}

$recordsRetrievalStrategy = new GetRecordsRetrievalStrategy();
$records = [];
$queueSize = 0;

function startRetrieving($recordsRetrievalStrategy, &$records, &$queueSize) {
    while ($queueSize < 3) {
        $response = $recordsRetrievalStrategy->getRecords(1000);
        $records = array_merge($records, $response->records);
        $queueSize += count($response->records);
    }
}

startRetrieving($recordsRetrievalStrategy, $records, $queueSize);

if ($queueSize >= $maxSize) {
    echo "Queue has enough records: ".$queueSize.PHP_EOL;
}
--EXPECT--
