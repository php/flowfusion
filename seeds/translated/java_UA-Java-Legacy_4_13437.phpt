--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/4)
--FILE--
<?php

$requestId = 1;
$maxMessageSize = 100;
$contentLength = 120; // Example to trigger the error

if ($contentLength > $maxMessageSize) {
    http_response_code(500);
    echo "No request message";
    exit;
}

$data = str_repeat('A', $contentLength); // Simulating the decoding process
$decodedMessage = null;

try {
    // Simulate a decoding process that can throw an exception
    if (strlen($data) > 100) {
        throw new Exception("Decoding error");
    }
    $decodedMessage = "Decoded message"; // Normally would be the result of a decoding function
} catch (Exception $e) {
    http_response_code(400);
    echo "Bad Request: " . $e->getMessage();
    exit;
}

// Simulate handling the request
echo "Request handled successfully: " . $decodedMessage;
--EXPECT--
