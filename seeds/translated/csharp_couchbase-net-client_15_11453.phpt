--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/15)
--FILE--
<?php
$address = '127.0.0.1:11210';
[$host, $port] = explode(':', $address, 2);
$port = (int)$port;

$endpoint = parse_url('//' . $address, PHP_URL_HOST);
$endpointPort = (int)parse_url('//' . $address, PHP_URL_PORT);

$healthy = false;
$connectionPool = new stdClass();
$server = new stdClass();
$server->connectionPool = $connectionPool;
$server->healthy = $healthy;

$operation = new stdClass();
$response = new stdClass();
$response->success = true;
$response->cas = 0;
$response->value = new stdClass();
$response->status = 'success';
$response->message = '';

$uri1 = 'https://localhost:18092/' . 'default';
$uri2 = 'http://localhost:8092/' . 'default';

unset($server, $connectionPool, $operation, $response);
echo $host . ':' . $port . "\n";
echo $endpoint . ':' . $endpointPort . "\n";
echo $uri1 . "\n";
echo $uri2 . "\n";
?>
--EXPECT--
