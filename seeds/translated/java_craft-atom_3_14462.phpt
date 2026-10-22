--TEST--
Translated seed (inferredbugs-java: craft-atom/3)
--FILE--
<?php

$start = 0;
$state = $start;
$maxLineLength = 1024;

$contentEncodingIdentity = 'identity';
$contentEncodingGzip = 'gzip';
$contentEncodingDeflate = 'deflate';
$transferEncodingChunked = 'chunked';

$httpMessage = [
    'headers' => [],
    'entity' => null,
];

function state4ENTITY_ENCODING(&$httpMessage) {
    global $contentEncodingIdentity, $contentEncodingGzip, $contentEncodingDeflate;

    $coding = isset($httpMessage['headers']['Content-Encoding']) ? $httpMessage['headers']['Content-Encoding'] : $contentEncodingIdentity;

    if ($coding === $contentEncodingIdentity) {
        // No action needed for identity encoding
    } elseif ($coding === $contentEncodingGzip) {
        throw new Exception("unsupported content encoding=" . $coding);
    } elseif ($coding === $contentEncodingDeflate) {
        throw new Exception("unsupported content encoding=" . $coding);
    } else {
        throw new Exception("unsupported content encoding=" . $coding);
    }
}

function state4ENTITY_LENGTH(&$httpMessage) {
    if (!isset($httpMessage['headers']['Content-Length'])) {
        return;
    }

    $clen = (int)$httpMessage['headers']['Content-Length'];
    if ($clen < 0) {
        throw new Exception("content length < 0");
    }

    $httpMessage['entity'] = str_repeat('A', $clen); // simulate content
}

function state4HEADER_VALUE_SUFFIX(&$httpMessage) {
    global $state;
    // Pretend to add header logic here
    $state = isset($httpMessage['entity']) ? 'ENTITY' : 'END';
}

$httpMessage['headers']['Content-Encoding'] = 'identity'; // Example header
$httpMessage['headers']['Content-Length'] = '10'; // Example header

try {
    state4ENTITY_ENCODING($httpMessage);
    state4ENTITY_LENGTH($httpMessage);
    state4HEADER_VALUE_SUFFIX($httpMessage);
} catch (Exception $e) {
    echo $e->getMessage();
}
--EXPECT--
