--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/10)
--FILE--
<?php

$SIZE_512_KB = 512 * 1024;
$SIZE_1_MB = 2 * $SIZE_512_KB;
$MAX_RECORDS_PER_CALL = 10000;
$MAX_SIZE = 5;
$MAX_RECORDS_COUNT = 15000;
$IDLE_MILLIS_BETWEEN_CALLS = 0;

$records = [];
$spyQueue = [];
$operation = "ProcessTask";
$record = str_repeat("\0", $SIZE_512_KB);

function addRecord(&$records, $record) {
    array_push($records, $record);
    return count($records);
}

function sleepFor($millis) {
    usleep($millis * 1000);
}

$recordCount = addRecord($records, $record);
if ($recordCount >= 1000) {
    $expectedRecords = $records;
    sleepFor(2000);
    $calls = ceil($MAX_RECORDS_COUNT / $recordCount);
    $queueSize = min(count($records), $MAX_SIZE);

    if ($queueSize === 3) {
        echo "Queue filled correctly.\n";
    } else {
        echo "Unexpected queue size: " . $queueSize . "\n";
    }
}
--EXPECT--
