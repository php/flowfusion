--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/7)
--FILE--
<?php
class Organization {
    public $Id;
    public $Name;
    public $Description;
}

$organization = new Organization();
$organization->Id = "org-123";
$organization->Name = "example";
$organization->Description = null;

$body = json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$create = [
    'method' => 'POST',
    'uri' => '/api/v2/orgs',
    'body' => $body,
];
$delete = [
    'method' => 'DELETE',
    'uri' => '/api/v2/orgs/' . $organization->Id,
];
$update = [
    'method' => 'PATCH',
    'uri' => '/api/v2/orgs/' . $organization->Id,
    'body' => $body,
];
$findById = [
    'method' => 'GET',
    'uri' => '/api/v2/orgs/' . $organization->Id,
];
$findAll = [
    'method' => 'GET',
    'uri' => '/api/v2/orgs',
];

echo $create['method'] . ' ' . $create['uri'] . ' ' . $create['body'] . PHP_EOL;
echo $delete['method'] . ' ' . $delete['uri'] . PHP_EOL;
echo $update['method'] . ' ' . $update['uri'] . ' ' . $update['body'] . PHP_EOL;
echo $findById['method'] . ' ' . $findById['uri'] . PHP_EOL;
echo $findAll['method'] . ' ' . $findAll['uri'] . PHP_EOL;
?>
--EXPECT--
