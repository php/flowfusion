--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/93)
--FILE--
<?php
$queueName = bin2hex(random_bytes(8));
$action = 'RECEIVE';
$spanName = 'Azure Queue Storage';
$transactionName = $spanName . ' ' . $action . ' from ' . $queueName;
$type = 'messaging';
$subtype = 'azure_queue_storage';
$serviceResource = $subtype . '/' . $queueName;
$destinationAddress = 'example.queue.core.windows.net';

$transaction = [
    'name' => $transactionName,
    'type' => $type,
];

$span = [
    'name' => $spanName . ' SEND to ' . $queueName,
    'type' => $type,
    'subtype' => $subtype,
    'action' => strtolower('SEND'),
    'context' => [
        'destination' => [
            'address' => $destinationAddress,
            'service' => [
                'name' => $subtype,
                'resource' => $serviceResource,
                'type' => $type,
            ],
        ],
    ],
];

echo $transaction['name'], "\n";
echo $span['name'], "\n";
echo $span['context']['destination']['service']['resource'], "\n";
--EXPECT--
