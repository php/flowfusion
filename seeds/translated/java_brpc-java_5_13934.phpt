--TEST--
Translated seed (inferredbugs-java: brpc-java/5)
--FILE--
<?php

$protocolType = 1; // Assume JSON protocol
$encoding = 'UTF-8';
$headers = [
    'Content-Type' => 'application/json',
    'Content-Length' => 0,
    'Connection' => 'keep-alive',
    'log-id' => uniqid()
];

$bodyData = [
    'jsonrpc' => '2.0',
    'method' => 'exampleMethod',
    'params' => ['param1', 'param2'],
    'id' => 1
];

$bodyJson = json_encode($bodyData);
$headers['Content-Length'] = strlen($bodyJson);

$httpRequest = "POST /exampleService/exampleMethod HTTP/1.1\r\n";
foreach ($headers as $key => $value) {
    $httpRequest .= "$key: $value\r\n";
}
$httpRequest .= "\r\n" . $bodyJson;

$httpResponse = "HTTP/1.1 200 OK\r\n";
$httpResponse .= "Content-Type: application/json\r\n";
$httpResponse .= "Content-Length: " . strlen($bodyJson) . "\r\n";
$httpResponse .= "\r\n" . $bodyJson;

echo "Request:\n$httpRequest\n";
echo "Response:\n$httpResponse\n";
--EXPECT--
