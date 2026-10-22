--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/9)
--FILE--
<?php

$SIZE_512_KB = 512 * 1024;
$SIZE_1_MB = 2 * $SIZE_512_KB;
$MAX_RECORDS_PER_CALL = 10000;
$MAX_SIZE = 5;
$MAX_RECORDS_COUNT = 15000;

$records = [];
$queue = [];
$operation = "ProcessTask";

function createByteBufferWithSize($size) {
    return str_repeat('x', $size);
}

function getRecords($maxRecordsPerCall) {
    global $records;
    return array_slice($records, 0, min($maxRecordsPerCall, count($records)));
}

function processRecords($records) {
    global $queue, $MAX_SIZE;
    $queue = array_merge($queue, $records);
    if (count($queue) > $MAX_SIZE) {
        array_splice($queue, $MAX_SIZE);
    }
}

function sleepMillis($millis) {
    usleep($millis * 1000);
}

// Simulate adding data
for ($i = 0; $i < 500; $i++) {
    $records[] = createByteBufferWithSize($SIZE_512_KB);
}

processRecords(getRecords($MAX_RECORDS_PER_CALL));
sleepMillis(2000);
processRecords(getRecords($MAX_RECORDS_PER_CALL));
sleepMillis(2000);
processRecords(getRecords($MAX_RECORDS_PER_CALL));

echo count($queue) . "\n";
--EXPECT--
