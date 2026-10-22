--TEST--
Translated seed (inferredbugs-csharp: pulsar-dotpulsar/1)
--FILE--
<?php
$disposed = 0;
$serviceUrl = new stdClass();
$serviceUrl->url = 'pulsar://localhost:6650';

if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGINT, SIG_IGN);
}

if (($disposed ?? 0) !== 0) {
    throw new RuntimeException('disposed');
}

$correlationId = bin2hex(random_bytes(16));
$stateManager = ['Disconnected', 'Closed', 'Faulted'];
$producer = [
    'id' => $correlationId,
    'serviceUrl' => $serviceUrl,
    'topic' => 'persistent://public/default/topic',
    'initialSequenceId' => 0,
    'state' => $stateManager[0],
];

$consumer = [
    'id' => bin2hex(random_bytes(16)),
    'serviceUrl' => $serviceUrl,
    'subscriptionName' => 'sub',
    'topic' => 'persistent://public/default/topic',
    'state' => $stateManager[0],
];

$reader = [
    'id' => bin2hex(random_bytes(16)),
    'serviceUrl' => $serviceUrl,
    'topic' => 'persistent://public/default/topic',
    'state' => $stateManager[0],
];

$disposed = 1;
$disposed = $disposed ? 1 : 0;

if ($disposed !== 0) {
    $producer = null;
    $consumer = null;
    $reader = null;
}

echo "";
?>
--EXPECT--
