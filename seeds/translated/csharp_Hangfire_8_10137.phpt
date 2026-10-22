--TEST--
Translated seed (inferredbugs-csharp: Hangfire/8)
--FILE--
<?php
$queues = ['default', 'critical'];
$pollInterval = 1;
$fetchTimeout = 5;
$serverName = 'php-server-' . getmypid();

if ($queues === null) {
    throw new InvalidArgumentException('queues');
}
if ($pollInterval < 0) {
    throw new InvalidArgumentException('pollInterval');
}

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$serverKey = "hangfire:server:$serverName";
$serverQueuesKey = "hangfire:server:$serverName:queues";

$redis->multi();
$redis->sAdd('hangfire:servers', $serverName);
$redis->hMSet($serverKey, [
    'WorkerCount' => '1',
    'StartedAt' => (string)time(),
]);
foreach ($queues as $queue) {
    $redis->rPush($serverQueuesKey, $queue);
}
$redis->exec();

$redis->hSet($serverKey, 'Heartbeat', (string)time());

$redis->multi();
$redis->sRem('hangfire:servers', $serverName);
$redis->del([$serverKey, $serverQueuesKey]);
$redis->exec();
?>
--EXPECT--
