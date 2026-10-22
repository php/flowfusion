--TEST--
Translated seed (inferredbugs-csharp: invest-openapi-csharp-sdk/2)
--FILE--
<?php
$baseUri = 'https://api-invest.tinkoff.ru/openapi/';
$sandboxBaseUri = 'https://api-invest.tinkoff.ru/openapi/sandbox/';
$webSocketBaseUri = 'wss://api-invest.tinkoff.ru/openapi/md/v1/md-openapi/ws';

$token = str_repeat('a', 1);

$c1 = ['base' => $baseUri, 'ws' => $webSocketBaseUri, 'token' => $token, 'client' => new stdClass()];
$c2 = ['base' => $sandboxBaseUri, 'ws' => $webSocketBaseUri, 'token' => $token, 'client' => new stdClass()];

echo $c1['base'] . "\n" . $c2['base'] . "\n" . $c1['ws'] . "\n" . $c2['ws'] . "\n";
?>
--EXPECT--
