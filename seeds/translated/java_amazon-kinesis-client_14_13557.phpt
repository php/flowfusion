--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/14)
--FILE--
<?php

$size512KB = 512 * 1024;
$size1MB = 2 * $size512KB;
$maxRecordsPerCall = 10000;
$maxSize = 5;
$maxRecordsCount = 15000;
$idleMillisBetweenCalls = 0;

$records = [];
$queue = [];
$recordsRetrievedCount = 0;

function createByteBufferWithSize($size) {
    return str_repeat("\0", $size);
}

function getRecords($count) {
    global $size512KB, $records;
    for ($i = 0; $i < $count; $i++) {
        $records[] = createByteBufferWithSize($size512KB);
    }
    return $records;
}

function processRecordsInput() {
    global $records;
    return count($records);
}

function simulateFetchingRecords() {
    global $maxRecordsCount, $maxRecordsPerCall, $recordsRetrievedCount, $queue;

    while ($recordsRetrievedCount < $maxRecordsCount) {
        $fetchedRecords = getRecords($maxRecordsPerCall);
        $queue = array_merge($queue, $fetchedRecords);
        $recordsRetrievedCount += count($fetchedRecords);
        usleep($idleMillisBetweenCalls * 1000);
        
        if (count($queue) >= $maxSize) {
            break;
        }
    }
}

function startFetching() {
    simulateFetchingRecords();
}

startFetching();

$recordCount = processRecordsInput();
if ($recordCount > 0) {
    $queueSize = count($queue);
}

while (count($queue) < $maxSize) {
    usleep(1000);
}

exit(0);
--EXPECT--
