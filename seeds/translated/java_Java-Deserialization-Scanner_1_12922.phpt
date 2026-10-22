--TEST--
Translated seed (inferredbugs-java: Java-Deserialization-Scanner/1)
--FILE--
<?php

$serializeMagic = "\xBE\xB8";
$base64Magic = "\x72\x4F\x30\x41";
$asciiHexMagic = "\x61\x63\x65\x64";
$gzipMagic = "\x1F\x8B";
$base64GzipMagic = "\x48\x34\x73\x49";

// Simulated payload for deserialization
$payloads = [
    "Apache Commons Collections 3" => base64_encode("Sample payload data 1"),
    "Apache Commons Collections 4" => base64_encode("Sample payload data 2"),
];

function scanRequest($request) {
    global $serializeMagic, $base64Magic, $asciiHexMagic, $gzipMagic, $base64GzipMagic, $payloads;

    $hasVulnerability = false;

    // Check for magic bytes in the request
    if (strpos($request, $serializeMagic) !== false || 
        strpos($request, $base64Magic) !== false || 
        strpos($request, $asciiHexMagic) !== false || 
        strpos($request, $gzipMagic) !== false || 
        strpos($request, $base64GzipMagic) !== false) {
        
        $hasVulnerability = true;
    }

    // If vulnerability exists, simulate sending payloads
    if ($hasVulnerability) {
        foreach ($payloads as $key => $payload) {
            // Simulate alteration of the request
            $modifiedRequest = str_replace($serializeMagic, $payload, $request);
            // Simulate HTTP request
            $responseTime = rand(1, 100); // Simulate response time

            if ($responseTime > 10) {
                echo "Vulnerable: $key\n";
            }
        }
    }
}

// Simulated HTTP request
$request = "Sample request data with serialized object ".$serializeMagic;
scanRequest($request);
--EXPECT--
