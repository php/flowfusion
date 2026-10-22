--TEST--
Translated seed (inferredbugs-java: amazon-kinesis-client/4)
--FILE--
<?php

$streamName = "example_stream";
$shardId = "shardId-000000000000";
$maxRecords = 10;
$describeStreamBackoffTimeInMillis = 1000;
$maxDescribeStreamRetryAttempts = 50;

$shards = [];
$remainingRetryTimes = $maxDescribeStreamRetryAttempts;
$response = null;

while ($remainingRetryTimes >= 0 && $response === null) {
    $response = describeStream($streamName, $shardId);
    if ($response === null) {
        $remainingRetryTimes--;
        usleep($describeStreamBackoffTimeInMillis * 1000);
    }
}

if ($response !== null && in_array($response['StreamStatus'], ['ACTIVE', 'UPDATING'])) {
    $shards = $response['Shards'];
}

foreach ($shards as $shard) {
    if ($shard['ShardId'] === $shardId) {
        $finalShard = $shard;
        break;
    }
}

if (isset($finalShard)) {
    echo "Found shard: " . $finalShard['ShardId'] . PHP_EOL;
}

function describeStream($streamName, $exclusiveStartShardId) {
    // Mock function simulating a description of a stream.
    // In reality, this would interact with an external service.
    return [
        'StreamStatus' => 'ACTIVE',
        'Shards' => [['ShardId' => 'shardId-000000000000'], ['ShardId' => 'shardId-000000000001']],
    ];
}
--EXPECT--
