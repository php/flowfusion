--TEST--
Translated seed (inferredbugs-csharp: Hangfire/9)
--FILE--
<?php
$queues = ['default', 'critical'];
$serverName = 'srv:' . bin2hex(random_bytes(4));
$workerCount = 2;
$pollInterval = 1;
$fetchTimeout = 5;

if ($queues === null) {
    throw new InvalidArgumentException('queues');
}
if ($pollInterval < 0) {
    throw new InvalidArgumentException('pollInterval');
}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$serverKey = "hangfire:server:$serverName";
$queuesKey = "hangfire:server:$serverName:queues";

$redis->multi(Redis::MULTI);
$redis->sAdd('hangfire:servers', $serverName);
$redis->hMSet($serverKey, [
    'WorkerCount' => (string)$workerCount,
    'StartedAt' => (string)time(),
]);
foreach ($queues as $queue) {
    $redis->rPush($queuesKey, $queue);
}
$redis->exec();

$redis->hSet($serverKey, 'Heartbeat', (string)time());

$redis->multi(Redis::MULTI);
$redis->sRem('hangfire:servers', $serverName);
$redis->del($serverKey, $queuesKey);
$redis->exec();
?>
--EXPECT--
