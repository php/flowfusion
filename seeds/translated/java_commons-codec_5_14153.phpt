--TEST--
Translated seed (inferredbugs-java: commons-codec/5)
--FILE--
<?php

function base64_encode_php($data) {
    return base64_encode($data);
}

function base64_decode_php($data) {
    return base64_decode($data);
}

$content = "Hello World";
$encodedContent = base64_encode_php($content);
if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
    exit("Encoding failed");
}

$encodedWithInvalidChars = "SGVsbG{}8gV29ybGQ=";
$decodedContent = base64_decode_php($encodedWithInvalidChars);
if ($decodedContent !== false) {
    exit("Decoding should have failed");
}

$encodedBytes = base64_encode_php($content);
$decodedBytes = base64_decode_php($encodedBytes);
if ($decodedBytes !== $content) {
    exit("Decoded content does not match original");
}

$invalidBytes = [0x9c];
$decodedInvalid = base64_decode_php(base64_encode_php(implode(array_map('chr', $invalidBytes))));
if ($decodedInvalid !== false) {
    exit("Decoding invalid characters should not succeed");
}

$empty = '';
$encodedEmpty = base64_encode_php($empty);
if ($encodedEmpty !== "") {
    exit("Encoding empty string did not result in empty string");
}

$decodedEmpty = base64_decode_php($encodedEmpty);
if ($decodedEmpty !== "") {
    exit("Decoding empty string did not result in empty string");
}
--EXPECT--
