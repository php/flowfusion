--TEST--
Translated seed (inferredbugs-java: commons-codec/17)
--FILE--
<?php

function isBase64($data) {
    return preg_match('/^(?:[A-Za-z0-9+\/=]+\n?)*$/', $data);
}

function decodeBase64($data) {
    return base64_decode(str_replace(array("\n", "\r"), '', $data));
}

function encodeBase64($data) {
    return base64_encode($data);
}

$content = "Hello World";
$encodedContent = encodeBase64($content);
$decodedContent = decodeBase64($encodedContent);

if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
    throw new Exception("Encoding failed");
}

if ($decodedContent !== $content) {
    throw new Exception("Decoding failed");
}

$invalidString = "SGVsbG{\xe9\xe9\xe9\xe9\xe9\xe9}8gV29ybGQ=";

if (isBase64($invalidString)) {
    throw new Exception("Invalid string was incorrectly recognized as base64");
}

$nullString = null;
try {
    isBase64($nullString);
} catch (TypeError $e) {
    // Expected TypeError when passing null
}

$emptyString = "";
if (!isBase64($emptyString)) {
    throw new Exception("Empty string should be recognized as base64");
}

$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
if (!isBase64($validString)) {
    throw new Exception("Valid string was not recognized as base64");
}

$invalidString = $validString . "\0"; // append null character
if (isBase64($invalidString)) {
    throw new Exception("Invalid string was incorrectly recognized as base64");
}

$randomData = random_bytes(10);
$encodedRandom = encodeBase64($randomData);
$decodedRandom = decodeBase64($encodedRandom);
if ($randomData !== $decodedRandom) {
    throw new Exception("Random data did not match after encode/decode");
}

$smallDataTests = [
    '',
    'f',
    'fo',
    'foo',
    'foob',
    'foobar'
];

foreach ($smallDataTests as $data) {
    $encoded = encodeBase64($data);
    $decoded = decodeBase64($encoded);
    if ($data !== $decoded) {
        throw new Exception("Failed on test data: $data");
    }
}
--EXPECT--
