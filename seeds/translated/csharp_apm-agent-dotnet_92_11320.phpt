--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/92)
--FILE--
<?php
$action = 'GET';
$resource = 'container/blob';
$spanName = 'AzureBlobStorage';
$type = 'storage';
$subtype = 'azure_blob';
$accountName = 'account';
$namespace = 'account.blob.core.windows.net';

$span = [
    'name' => $spanName . ' ' . $action . ' ' . $resource,
    'type' => $type,
    'subtype' => $subtype,
    'action' => $action,
    'context' => [
        'destination' => [
            'address' => $namespace,
            'service' => [
                'name' => $subtype,
                'resource' => $subtype . '/' . $accountName,
                'type' => $type,
            ],
        ],
    ],
];

$spans = [$span];
if (count($spans) !== 1) {
    throw new Exception('Unexpected span count');
}

$first = $spans[0];
if ($first['name'] !== $spanName . ' ' . $action . ' ' . $resource) {
    throw new Exception('Unexpected span name');
}
if ($first['type'] !== $type || $first['subtype'] !== $subtype || $first['action'] !== $action) {
    throw new Exception('Unexpected span fields');
}
if (!isset($first['context']['destination'])) {
    throw new Exception('Missing destination');
}
$destination = $first['context']['destination'];
if ($destination['address'] !== $namespace) {
    throw new Exception('Unexpected destination address');
}
if ($destination['service']['name'] !== $subtype) {
    throw new Exception('Unexpected service name');
}
if ($destination['service']['resource'] !== $subtype . '/' . $accountName) {
    throw new Exception('Unexpected service resource');
}
if ($destination['service']['type'] !== $type) {
    throw new Exception('Unexpected service type');
}
?>
--EXPECT--
