--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/5)
--FILE--
<?php

$shardIterator = '';
$maxRecords = 10;

$describeStreamBackoffTimeInMillis = 1000;
$maxDescribeStreamRetryAttempts = 50;
$listShardsBackoffTimeInMillis = 1000;
$maxListShardsRetryAttempts = 50;

$listOfShardsSinceLastGet = null;

function getRecords($shardIterator, $maxRecords) {
    // Simulate getting records with random success/failure
    if (rand(0, 1)) {
        return ['records' => [], 'nextShardIterator' => 'nextIterator'];
    } else {
        throw new Exception('ExpiredIteratorException');
    }
}

function listShards($nextToken) {
    // Simulate listing shards with random success/failure
    if (rand(0, 1)) {
        return ['shards' => ['shard1', 'shard2'], 'nextToken' => null];
    } else {
        throw new Exception('LimitExceededException');
    }
}

function sleepMillis($milliseconds) {
    usleep($milliseconds * 1000);
}

$remainingRetryAttempts = $maxDescribeStreamRetryAttempts;
$response = null;

while ($response === null) {
    try {
        $response = listShards(null);
    } catch (Exception $e) {
        if ($e->getMessage() === 'LimitExceededException') {
            sleepMillis($describeStreamBackoffTimeInMillis);
        }
        $remainingRetryAttempts--;
        if ($remainingRetryAttempts <= 0) {
            throw new Exception('Failed to list shards.');
        }
    }
}

$listOfShardsSinceLastGet = $response['shards'];

foreach ($listOfShardsSinceLastGet as $shard) {
    $remainingRetryAttempts = $maxListShardsRetryAttempts;
    $response = null;

    while ($response === null) {
        try {
            $response = getRecords($shardIterator, $maxRecords);
        } catch (Exception $e) {
            if ($e->getMessage() === 'ExpiredIteratorException') {
                // Handle expired iterator case
                sleepMillis($listShardsBackoffTimeInMillis);
            }
            $remainingRetryAttempts--;
            if ($remainingRetryAttempts <= 0) {
                throw new Exception('Failed to get records.');
            }
        }
    }
}
--EXPECT--
