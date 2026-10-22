--TEST--
Translated seed (inferredbugs-csharp: Hangfire/10)
--FILE--
<?php
$queues = ['default', 'critical'];
$pollInterval = 5;
$fetchTimeout = 1;
$serverName = 'php-server-' . mt_rand();

$redis = [];
$redis['hangfire:servers'] = [];
$redis['hangfire:server:' . $serverName] = [];
$redis['hangfire:server:' . $serverName . ':queues'] = [];

$redis['hangfire:servers'][] = $serverName;
$redis['hangfire:server:' . $serverName]['WorkerCount'] = '4';
$redis['hangfire:server:' . $serverName]['StartedAt'] = (string) time();

foreach ($queues as $queue) {
    $redis['hangfire:server:' . $serverName . ':queues'][] = $queue;
}

$attempts = 10;
for ($i = 0; $i < $attempts; $i++) {
    try {
        if ($i < 2) {
            throw new Exception('transient');
        }
        $redis['hangfire:server:' . $serverName]['Heartbeat'] = (string) time();
        break;
    } catch (Throwable $e) {
        if ($i === $attempts - 1) {
            throw $e;
        }
        $nextTry = random_int($i * $i, ($i + 1) * ($i + 1));
        if ($nextTry < 0) {
            break;
        }
    }
}

unset($redis['hangfire:servers'][array_search($serverName, $redis['hangfire:servers'], true)]);
unset($redis['hangfire:server:' . $serverName], $redis['hangfire:server:' . $serverName . ':queues']);

echo "";
?>
--EXPECT--
