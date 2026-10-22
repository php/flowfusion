--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/4)
--FILE--
<?php
$serverConfig = (object)['Buckets' => [(object)['Name' => 'bucket1']]];
$clientConfig = (object)[];
$threads = [];
$listeners = [];
$configs = [];
$cancellationTokens = [];

$listener = (object)[
    'Name' => 'bucket1',
    'NotifyConfigChanged' => function ($configInfo) {
        if (($configInfo->BucketConfig->Name ?? null) === 'bucket1') {
            return;
        }
    }
];

$bucketConfig = null;
foreach ($serverConfig->Buckets as $x) {
    if ($x->Name === $listener->Name) {
        $bucketConfig = $x;
        break;
    }
}
if ($bucketConfig === null) {
    throw new Exception('Bucket not found');
}

$cancellationTokens[$listener->Name] = true;
$threads[$listener->Name] = true;
$listeners[$listener->Name] = $listener;

if (!isset($configs[$bucketConfig->Name])) {
    $configInfo = (object)['BucketConfig' => $bucketConfig, 'ClientConfig' => $clientConfig, 'ServerConfig' => $serverConfig];
    $configs[$bucketConfig->Name] = $configInfo;
} else {
    $configInfo = $configs[$bucketConfig->Name];
    if ($configInfo->BucketConfig == $bucketConfig) {
        $configInfo = null;
    } else {
        $configInfo = (object)['BucketConfig' => $bucketConfig, 'ClientConfig' => $clientConfig, 'ServerConfig' => $serverConfig];
    }
}

if ($configInfo !== null) {
    ($listener->NotifyConfigChanged)($configInfo);
}

unset($threads[$listener->Name]);
unset($cancellationTokens[$listener->Name]);
unset($listeners[$listener->Name]);
unset($configs[$bucketConfig->Name]);
--EXPECT--
