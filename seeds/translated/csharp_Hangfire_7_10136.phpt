--TEST--
Translated seed (inferredbugs-csharp: Hangfire/7)
--FILE--
<?php
$serverName = 'server:' . uniqid();
$workerCount = 2;
$queues = ['default', 'critical'];
$store = [];

$store['hangfire:servers'][$serverName] = true;
$store["hangfire:server:$serverName"] = [
    'WorkerCount' => (string)$workerCount,
    'StartedAt' => gmdate('Y-m-d\TH:i:s\Z'),
    'Heartbeat' => gmdate('Y-m-d\TH:i:s\Z'),
];
$store["hangfire:server:$serverName:queues"] = [];
foreach ($queues as $queue) {
    $store["hangfire:server:$serverName:queues"][] = $queue;
}

$store["hangfire:server:$serverName"]['Heartbeat'] = gmdate('Y-m-d\TH:i:s\Z');

unset($store['hangfire:servers'][$serverName], $store["hangfire:server:$serverName"], $store["hangfire:server:$serverName:queues"]);

echo "ok\n";
?>
--EXPECT--
