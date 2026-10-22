--TEST--
Translated seed (inferredbugs-csharp: Hangfire/5)
--FILE--
<?php
$queues = ['default', 'critical'];
if ($queues === null) {
    throw new Exception('queues');
}
$pollInterval = 5.0;
if ($pollInterval < 0) {
    throw new Exception('pollInterval');
}

$machineName = php_uname('n');
$serverName = $machineName . ':' . getmypid();
$serversKey = 'hangfire:servers';
$serverKey = 'hangfire:server:' . $serverName;
$queuesKey = $serverKey . ':queues';

$redis = [];
$redis[$serversKey][$serverName] = true;
$redis[$serverKey] = [
    'WorkerCount' => '2',
    'StartedAt' => (string) time(),
];
foreach ($queues as $queue) {
    $redis[$queuesKey][] = $queue;
}

for ($i = 0; $i < 3; $i++) {
    $redis[$serverKey]['Heartbeat'] = (string) time();
}

unset($redis[$serversKey][$serverName]);
unset($redis[$serverKey], $redis[$queuesKey]);

echo "";
?>
--EXPECT--
