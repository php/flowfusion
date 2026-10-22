--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/90)
--FILE--
<?php
$queueName = bin2hex(random_bytes(16));
$messages = [];
$spans = [];

$messages[] = ['action' => 'SEND', 'queue' => $queueName, 'name' => "AzureQueueStorage SEND to $queueName", 'type' => 'messaging', 'subtype' => 'azurequeue', 'destination' => ['address' => 'example.queue.core.windows.net', 'service' => ['name' => 'azurequeue', 'resource' => "azurequeue/$queueName", 'type' => 'messaging']]];
$messages[] = ['action' => 'RECEIVE', 'queue' => $queueName, 'name' => "AzureQueueStorage RECEIVE from $queueName", 'type' => 'messaging'];

foreach ($messages as $m) {
    if ($m['action'] === 'SEND') {
        $spans[] = $m;
    } else {
        $spans[] = $m;
    }
}

if (count($spans) !== 2) {
    throw new Exception('unexpected span count');
}

foreach ($spans as $span) {
    if ($span['type'] !== 'messaging') {
        throw new Exception('unexpected type');
    }
    if ($span['action'] === 'SEND') {
        if (($span['subtype'] ?? null) !== 'azurequeue') {
            throw new Exception('unexpected subtype');
        }
        if (($span['destination']['service']['resource'] ?? null) !== "azurequeue/$queueName") {
            throw new Exception('unexpected resource');
        }
    }
}

echo "";
?>
--EXPECT--
