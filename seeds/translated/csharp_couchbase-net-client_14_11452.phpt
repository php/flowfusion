--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/14)
--FILE--
<?php
$address = '127.0.0.1:11210';
[$host, $port] = explode(':', $address, 2);
$port = (int)$port;

$healthy = false;
$connectionPool = new stdClass();
$server = new stdClass();
$server->healthy = $healthy;
$server->connectionPool = $connectionPool;
$server->encryptTraffic = true;
$server->baseViewHost = 'localhost';

$endpoint = explode(':', '192.168.56.101:11210', 2);
$endpointHost = $endpoint[0];
$endpointPort = (int)$endpoint[1];

$uri = ($server->encryptTraffic ? 'https' : 'http') . '://' . $server->baseViewHost . ':' . ($server->encryptTraffic ? 18092 : 8092) . '/default';

$server->encryptTraffic = false;
$uri2 = ($server->encryptTraffic ? 'https' : 'http') . '://' . $server->baseViewHost . ':' . ($server->encryptTraffic ? 18092 : 8092) . '/default';

echo '';
?>
--EXPECT--
