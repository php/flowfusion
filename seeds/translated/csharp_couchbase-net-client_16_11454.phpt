--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/16)
--FILE--
<?php
$address = '127.0.0.1:11210';
[$host, $port] = explode(':', $address, 2);
$port = (int)$port;
$healthy = false;
$connectionPool = ['host' => $host, 'port' => $port];
$response = ['success' => true, 'cas' => 0, 'value' => new stdClass(), 'status' => 'Success', 'message' => ''];
$endpoint = explode(':', '192.168.56.101:11210', 2);
$endpointHost = $endpoint[0];
$endpointPort = (int)$endpoint[1];
$encryptTraffic = true;
$baseViewUri = ($encryptTraffic ? 'https' : 'http') . '://localhost:' . ($encryptTraffic ? '18092' : '8092') . '/default';
$encryptTraffic = false;
$baseViewUri2 = ($encryptTraffic ? 'https' : 'http') . '://localhost:' . ($encryptTraffic ? '18092' : '8092') . '/default';
echo '';
?>
--EXPECT--
