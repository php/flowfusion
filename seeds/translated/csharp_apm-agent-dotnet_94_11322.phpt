--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/94)
--FILE--
<?php
$items = [
    ['action' => 'Create', 'resource' => 'share/' . bin2hex(random_bytes(8))],
    ['action' => 'Delete', 'resource' => 'share/' . bin2hex(random_bytes(8))],
    ['action' => 'Create', 'resource' => 'share/' . bin2hex(random_bytes(8)) . '/' . bin2hex(random_bytes(8))],
    ['action' => 'Delete', 'resource' => 'share/' . bin2hex(random_bytes(8)) . '/' . bin2hex(random_bytes(8))],
    ['action' => 'Upload', 'resource' => 'share/' . bin2hex(random_bytes(8)) . '/' . bin2hex(random_bytes(8)) . '/' . bin2hex(random_bytes(8))],
];

foreach ($items as $item) {
    $payload = "temp file";
    $bytes = strlen($payload);
    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $payload);
    rewind($stream);
    $chunk = stream_get_contents($stream, $bytes);
    fclose($stream);
    $span = "AzureFileStorage {$item['action']} {$item['resource']}";
    $meta = [
        'name' => $span,
        'type' => 'storage',
        'subtype' => 'azurefiles',
        'action' => $item['action'],
        'destination' => [
            'address' => 'example.file.core.windows.net',
            'service' => [
                'name' => 'azurefiles',
                'resource' => 'azurefiles/exampleaccount',
                'type' => 'storage',
            ],
        ],
    ];
    if ($chunk === '') {
        continue;
    }
    $meta['resource_length'] = $bytes;
    $meta['chunk_length'] = strlen($chunk);
}
?>
--EXPECT--
