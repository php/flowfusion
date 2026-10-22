--TEST--
Translated seed (inferredbugs-csharp: influxdb-client-csharp/6)
--FILE--
<?php
$organization = (object)[
    'Id' => "0\0/../\u{2603}",
    'Name' => "test",
    'Description' => null,
    'Extra' => ['a' => 1, 'b' => null, 'c' => [true, false, null]],
];

$create = [
    'method' => 'POST',
    'uri' => '/api/v2/orgs',
    'body' => json_encode(array_filter(get_object_vars($organization), fn($v) => $v !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
];

$delete = [
    'method' => 'DELETE',
    'uri' => '/api/v2/orgs/' . $organization->Id,
];

$update = [
    'method' => 'PATCH',
    'uri' => '/api/v2/orgs/' . $organization->Id,
    'body' => json_encode(array_filter(get_object_vars($organization), fn($v) => $v !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
];

$findById = [
    'method' => 'GET',
    'uri' => '/api/v2/orgs/' . $organization->Id,
];

$findAll = [
    'method' => 'GET',
    'uri' => '/api/v2/orgs',
];

echo $create['method'] . ' ' . $create['uri'] . ' ' . $create['body'] . "\n";
echo $delete['method'] . ' ' . $delete['uri'] . "\n";
echo $update['method'] . ' ' . $update['uri'] . ' ' . $update['body'] . "\n";
echo $findById['method'] . ' ' . $findById['uri'] . "\n";
echo $findAll['method'] . ' ' . $findAll['uri'] . "\n";
?>
--EXPECT--
