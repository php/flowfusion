--TEST--
Translated seed (inferredbugs-csharp: Hangfire/2)
--FILE--
<?php
$queues = ['default', 'critical', 'default'];
$concurrency = 1;
$pollInterval = 0.1;
$machineName = gethostname();
$serverName = $machineName . ':' . getmypid();

$servers = [];
$serverHash = [];
$serverQueues = [];

$servers[$serverName] = true;
$serverHash[$serverName] = [
    'Workers' => (string)$concurrency,
    'StartedAt' => gmdate('c'),
];

foreach ($queues as $queue) {
    $serverQueues[$serverName . ':queues'][$queue] = true;
}

unset($servers[$serverName]);
unset($serverHash[$serverName], $serverQueues[$serverName . ':queues']);

echo "";
?>
--EXPECT--
