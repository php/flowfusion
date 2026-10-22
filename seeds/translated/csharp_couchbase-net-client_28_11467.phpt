--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/28)
--FILE--
<?php
$cfg = [
    'couchbaseClients/couchbase' => [
        'useSsl' => false,
        'servers' => ['http://localhost:8091'],
        'sslPort' => 11207,
        'mgmtPort' => 8091,
        'apiPort' => 8092,
        'httpsMgmtPort' => 18091,
        'httpsApiPort' => 18092,
        'directPort' => 11210,
        'buckets' => [
            ['name' => 'default', 'password' => '', 'useSsl' => false, 'pool' => ['name' => 'default', 'maxSize' => 2, 'minSize' => 1, 'waitTimeout' => 2500, 'shutdownTimeout' => 10000]],
        ],
    ],
    'couchbaseClients/couchbase_1' => [
        'useSsl' => true,
        'servers' => ['https://localhost2:18099'],
        'sslPort' => 443,
        'mgmtPort' => 8095,
        'apiPort' => 8094,
        'httpsMgmtPort' => 18099,
        'httpsApiPort' => 18098,
        'directPort' => 11219,
        'buckets' => [
            ['name' => 'testbucket', 'password' => 'shhh!', 'useSsl' => true, 'pool' => ['name' => 'custom', 'maxSize' => 10, 'minSize' => 5, 'waitTimeout' => 5000, 'shutdownTimeout' => 3000]],
        ],
    ],
    'couchbaseClients/couchbase_2' => [
        'useSsl' => false,
        'servers' => ['http://localhost:8091'],
        'buckets' => [
            ['name' => 'default', 'password' => '', 'useSsl' => true, 'pool' => ['name' => 'default', 'maxSize' => 2, 'minSize' => 1, 'waitTimeout' => 2500, 'shutdownTimeout' => 10000]],
        ],
    ],
];

$section = $cfg['couchbaseClients/couchbase'];
$servers = $section['servers'];
$buckets = $section['buckets'];
$bucket = $buckets[0];
$pool = $bucket['pool'];

$section = $cfg['couchbaseClients/couchbase_1'];
$buckets = $section['buckets'];
$bucket = $buckets[0];
$pool = $bucket['pool'];

$section = $cfg['couchbaseClients/couchbase_2'];
$buckets = $section['buckets'];
$bucket = $buckets[0];

$cluster = $cfg['couchbaseClients/couchbase'];
$bucketName = $cluster['buckets'][0]['name'];
$store = [];
$store['testkey'] = 'testvalue';
$value = $store['testkey'];

echo "";
?>
--EXPECT--
