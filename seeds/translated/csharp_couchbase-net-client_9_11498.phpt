--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/9)
--FILE--
<?php
$address = '127.0.0.1:11210';
$parts = explode(':', $address, 2);
$host = $parts[0];
$port = (int)$parts[1];

$healthy = false;
$connectionPool = new stdClass();

$operation = new stdClass();
$operation->type = 'ConfigOperation';

$response = new stdClass();
$response->Success = true;
$response->Cas = 0;
$response->Value = new stdClass();
$response->Status = 'Success';
$response->Message = '';

$endpointAddress = '192.168.56.101:11210';
$endpointParts = explode(':', $endpointAddress, 2);
$endpoint = new stdClass();
$endpoint->Address = $endpointParts[0];
$endpoint->Port = (int)$endpointParts[1];

unset($healthy, $connectionPool, $operation, $response, $endpoint);
?>
--EXPECT--
