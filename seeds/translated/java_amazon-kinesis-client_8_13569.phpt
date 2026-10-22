--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/8)
--FILE--
<?php

$SIZE_512_KB = 512 * 1024;
$SIZE_1_MB = 2 * $SIZE_512_KB;
$MAX_RECORDS_PER_CALL = 10000;
$MAX_SIZE = 5;
$MAX_RECORDS_COUNT = 15000;
$IDLE_MILLIS_BETWEEN_CALLS = 0;

$records = [];
$recordsCount = 0;

function createByteBufferWithSize($size) {
    return str_repeat("\0", $size);
}

$record = createByteBufferWithSize($SIZE_512_KB);

$recordsCount = 1000;

function getRecords($recordsCount) {
    global $MAX_RECORDS_PER_CALL;
    // Simulate getting records from a source
    return array_fill(0, min($MAX_RECORDS_PER_CALL, $recordsCount), $record);
}

$records = getRecords($recordsCount);

$expectedRecords = array_map(function($r) { return $r; }, $records);

$cache = [];
for ($i = 0; $i < 3; $i++) {
    $cache = array_merge($cache, getRecords($recordsCount));
    if (count($cache) >= $MAX_SIZE) {
        break;
    }
}

usleep(2000 * 1000);

if (count($cache) >= $MAX_SIZE) {
    exit(0);
} else {
    exit(1);
}
--EXPECT--
