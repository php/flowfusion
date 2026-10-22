--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/5)
--FILE--
<?php
$organization = [
    'name' => 'example-org',
    'description' => 'test',
    'active' => true,
    'members' => [],
];

$postRequest = [
    'method' => 'POST',
    'uri' => '/api/v2/orgs',
    'headers' => ['Content-Type' => 'application/json'],
    'body' => json_encode($organization, JSON_UNESCAPED_SLASHES),
];

$getRequest = [
    'method' => 'GET',
    'uri' => '/api/v2/orgs',
    'headers' => [],
    'body' => null,
];

echo $postRequest['method'] . ' ' . $postRequest['uri'] . "\n";
echo $postRequest['body'] . "\n";
echo $getRequest['method'] . ' ' . $getRequest['uri'] . "\n";
--EXPECT--
