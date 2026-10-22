--TEST--
Translated seed (inferredbugs-java: commons-codec/4)
--FILE--
<?php

function base64UrlSafeEncode($data) {
    $encoded = base64_encode($data);
    return rtrim(strtr($encoded, '+/', '-_'), '=');
}

function base64UrlSafeDecode($data) {
    $data .= str_repeat('=', (4 - strlen($data) % 4) % 4);
    $data = strtr($data, '-_', '+/');
    return base64_decode($data);
}

function decodeInvalidBase64() {
    return base64UrlSafeDecode("VGhlIH@$#$@%F1aWN@#@#@@rIGJyb3duIGZve\n\r\t%#%#%#%CBqd##$#$W1wZWQgb3ZlciB0aGUgbGF6eSBkb2dzLg==");
}

function forceError() {
    return base64UrlSafeDecode('Invalid base64 string');
}

function testUrlSafeBase64() {
    $data = "Hello World!";
    $encoded = base64UrlSafeEncode($data);
    $decoded = base64UrlSafeDecode($encoded);
    if ($decoded !== $data) {
        throw new Exception('Decoded data does not match original data');
    }
}

function main() {
    testUrlSafeBase64();
    decodeInvalidBase64();
    // Uncomment below to force an error for testing purposes
    // forceError();
}

main();
--EXPECT--
