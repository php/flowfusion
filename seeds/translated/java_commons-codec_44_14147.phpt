--TEST--
Translated seed (inferredbugs-java: commons-codec/44)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];
$stringFixture = "Hello World";

// Simulating reading from a Base64 encoded input stream
$inputStream = fopen('data://text/plain;base64,'.base64_encode($encodedB64), 'r');

// Reading and skipping bytes
fgets($inputStream); // Read first line
fseek($inputStream, 1, SEEK_CUR); // Skip first character

// Decode base64 to check the implementation
$decoded = base64_decode($encodedB64);
echo ($decoded === implode(',', $decodedBytes)) ? "Test passed!" : "Test failed!";
fclose($inputStream);
--EXPECT--
