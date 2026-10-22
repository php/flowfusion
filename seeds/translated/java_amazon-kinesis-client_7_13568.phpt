--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/7)
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
$record = str_repeat('a', $SIZE_512_KB);

function getRecords($num) {
    return array_fill(0, $num, str_repeat('a', 1024));
}

function sleepMillis($millis) {
    usleep($millis * 1000);
}

$recordsCount = count($records);
if ($recordsCount < 1000) {
    $records = getRecords(1000);
}

if (count($spyQueue) < $MAX_SIZE) {
    array_push($spyQueue, $record);
}

sleepMillis(2000);

$callRate = ceil($MAX_RECORDS_COUNT / $recordsCount);
if (count($spyQueue) <= $MAX_SIZE) {
    // Simulates processing
    echo "Processed " . count($spyQueue) . " records.\n";
}

$recordsCount = 4500;
if (count($records) < 500) {
    $records = getRecords(500);
}

sleepMillis(2000);

if (count($spyQueue) < $MAX_SIZE) {
    echo "No deadlock detected.\n";
}

while (count($spyQueue) < $MAX_SIZE) {
    $recordsCount++;
    array_push($spyQueue, $record);
}

$expectedItems = $MAX_SIZE * 3;
$receivedItems = 0;

while ($receivedItems < $expectedItems) {
    $receivedItems++;
    if ($receivedItems >= $expectedItems) {
        break;
    }
}

echo "Processed " . $receivedItems . " items successfully.\n";
--EXPECT--
