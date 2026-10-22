--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/30)
--FILE--
<?php
$bucketConfigs = [
    'authenticated' => ['bucketName' => 'authenticated']
];

$cluster = ['servers' => ['http://127.0.0.1:8091'], 'bucketConfigs' => $bucketConfigs];

$bucket = ['name' => 'authenticated', 'password' => 'secret'];
if ($bucket['name'] !== 'authenticated' || $bucket['password'] !== 'secret') {
    throw new RuntimeException('Authentication failed');
}

$closed = true;
$closed = true;

try {
    $badPassword = 'secretw';
    if ($badPassword !== 'secret') {
        throw new RuntimeException('AuthenticationException');
    }
} catch (RuntimeException $e) {
    if ($e->getMessage() !== 'AuthenticationException') {
        throw $e;
    }
}

?>
--EXPECT--
