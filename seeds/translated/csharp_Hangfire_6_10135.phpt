--TEST--
Translated seed (inferredbugs-csharp: Hangfire/6)
--FILE--
<?php
$queues = ['default', 'critical'];
$serverName = 'server:' . bin2hex(random_bytes(4));
$workerCount = 2;
$pollInterval = 1;
$fetchTimeout = 5;

if ($queues === null) {
    throw new InvalidArgumentException('queues');
}
if ($pollInterval < 0) {
    throw new OutOfRangeException('pollInterval');
}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$serversKey = 'hangfire:servers';
$serverKey = "hangfire:server:$serverName";
$queuesKey = "hangfire:server:$serverName:queues";

$redis->multi();
$redis->sAdd($serversKey, $serverName);
$redis->hMSet($serverKey, [
    'WorkerCount' => (string)$workerCount,
    'StartedAt' => (string)time(),
]);
foreach ($queues as $queue) {
    $redis->rPush($queuesKey, $queue);
}
$redis->exec();

$redis->hSet($serverKey, 'Heartbeat', (string)time());

$redis->multi();
$redis->sRem($serversKey, $serverName);
$redis->del([$serverKey, $queuesKey]);
$redis->exec();
?>
--EXPECT--
