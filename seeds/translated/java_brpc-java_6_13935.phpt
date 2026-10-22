--TEST--
Translated seed (inferredbugs-java: brpc-java/6)
--FILE--
<?php
$protocolType = 1; // Simulating Protocol Type
$encoding = 'utf-8'; // Simulating Encoding
$contentType = ($protocolType == 1) ? 'application/json' : 'application/proto';
$headers = [
    'Content-Type' => $contentType,
    'Content-Length' => 0,
    'Connection' => 'keep-alive',
    'correlation-id' => uniqid()
];

$requestBody = json_encode(['method' => 'testMethod', 'params' => [], 'id' => 1]);
$bodyBytes = $requestBody;

$headers['Content-Length'] = strlen($bodyBytes);

$httpRequest = implode("\r\n", [
    "POST /service/testMethod HTTP/1.1",
    "Host: localhost",
    "Content-Type: $contentType; charset=$encoding",
    "Content-Length: {$headers['Content-Length']}",
    "Connection: {$headers['Connection']}",
    "correlation-id: {$headers['correlation-id']}",
    "",
    $bodyBytes
]);

$httpResponseStatus = "HTTP/1.1 200 OK";
$responseBody = json_encode(['result' => 'success', 'id' => 1]);
$responseHeaders = "Content-Type: $contentType; charset=$encoding\r\nContent-Length: " . strlen($responseBody);

$response = "$httpResponseStatus\r\n$responseHeaders\r\n\r\n$responseBody";

$protocolTypeDecoded = strpos($response, 'application/json') !== false ? 1 : 2;
$responseData = json_decode(substr($response, strpos($response, "\r\n\r\n") + 4), true);

if ($protocolTypeDecoded == 1 && isset($responseData['result'])) {
    echo "Response: " . $responseData['result'];
} else {
    echo "Error in response";
}
?>
--EXPECT--
