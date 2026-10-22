--TEST--
Translated seed (inferredbugs-csharp: Hangfire/4)
--FILE--
<?php
$queues = ['default', 'critical'];
if ($queues === null) {
    throw new Exception('queues');
}

$pollInterval = 1.5;
if ($pollInterval < 0) {
    throw new Exception('pollInterval');
}

$machineName = php_uname('n');
$serverName = $machineName . ':' . getmypid();

$redis = new Redis();
if (!$redis->connect('127.0.0.1', 6379, 1.0)) {
    exit(0);
}

$serverKey = "hangfire:server:$serverName";
$queuesKey = "hangfire:server:$serverName:queues";

$redis->multi();
$redis->sAdd('hangfire:servers', $serverName);
$redis->hMSet($serverKey, [
    'WorkerCount' => (string)1,
    'StartedAt' => (string)time(),
]);
foreach ($queues as $queue) {
    $redis->rPush($queuesKey, $queue);
}
$redis->exec();

$redis->multi();
$redis->sRem('hangfire:servers', $serverName);
$redis->del([$serverKey, $queuesKey]);
$redis->exec();
?>
--EXPECT--
