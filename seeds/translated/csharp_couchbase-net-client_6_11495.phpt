--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/6)
--FILE--
<?php
declare(strict_types=1);

$bootstrap = __DIR__ . '/bootstrap.json';
file_put_contents($bootstrap, json_encode([
    'bucketType' => 'couchbase',
    'buckets' => [
        'default' => [
            'nodes' => [['hostname' => '127.0.0.1', 'port' => 11210]],
            'vBuckets' => 16
        ]
    ]
]));

$config = json_decode(file_get_contents($bootstrap), true);
$bucket = $config['buckets']['default'] ?? null;
if ($bucket === null) {
    throw new RuntimeException('missing bucket');
}

$key = 'TheKey';
$vbucket = crc32($key) % ($bucket['vBuckets'] ?? 1);
if ($vbucket < 0) {
    $vbucket += $bucket['vBuckets'];
}

if (($config['bucketType'] ?? null) !== 'couchbase') {
    throw new RuntimeException('wrong bucket type');
}

$nullConfig = null;
try {
    if ($nullConfig === null) {
        throw new RuntimeException('NullConfigException');
    }
} catch (RuntimeException $e) {
    if ($e->getMessage() !== 'NullConfigException') {
        throw $e;
    }
}
?>
--EXPECT--
