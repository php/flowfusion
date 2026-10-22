--TEST--
Translated seed (inferredbugs-java: commons-codec/51)
--FILE--
<?php

class Base64 {
    public static function isBase64($string) {
        return preg_match('/^[A-Za-z0-9+\/]*={0,2}$/', $string) === 1;
    }

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data, true);
    }
}

$data = "Hello World";
$encoded = Base64::encode($data);
$decoded = Base64::decode($encoded);

if ($encoded !== "SGVsbG8gV29ybGQ=") {
    throw new Exception("Encoding failed");
}

if ($decoded !== $data) {
    throw new Exception("Decoding failed");
}

$invalid = "SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=";
if (Base64::isBase64($invalid) !== true) {
    throw new Exception("Invalid Base64 format detected");
}

$valid = "SGVsbG8gV29ybGQ=";
if (Base64::isBase64($valid) !== true) {
    throw new Exception("Valid Base64 format not recognized");
}

$randomData = random_bytes(1024);
$encodedRandom = Base64::encode($randomData);
$decodedRandom = Base64::decode($encodedRandom);
if ($randomData !== $decodedRandom) {
    throw new Exception("Random data encoding/decoding failed");
}
--EXPECT--
