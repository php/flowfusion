--TEST--
Translated seed (inferredbugs-java: commons-codec/52)
--FILE--
<?php

$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
$invalidString = $validString . "\0"; // append null character

// Null check
try {
    base64_decode(null);
} catch (Throwable $e) {
    // expected
}

// Valid cases
var_dump(base64_encode("Hello World")); 
var_dump(base64_encode("Hello World") === "SGVsbG8gV29ybGQ=");

// Invalid case
var_dump(base64_decode($invalidString) === false);

// Handle decode with inner pad
$innerPad = "SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=";
$result = base64_decode($innerPad);
$expected = "Hello World";
var_dump($result === $expected);

// Handling characters that are not Base64
$nonBase64 = "VGhlIH@$#$@%F1aWN@#@#@@rIGJyb3duIGZve\n\r\t%#%#%#%CBqd##$#$W1wZWQgb3ZlciB0aGUgbGF6eSBkb2dzLg==";
$result = base64_decode($nonBase64);
var_dump($result === "The quick brown fox jumped over the lazy dogs.");

// Tests for empty base64
var_dump(base64_encode("") === "");
var_dump(base64_decode("") === "");

$data = random_bytes(100); // Simulating random data
$encodedData = base64_encode($data);
$decodedData = base64_decode($encodedData);
var_dump($decodedData === $data);
--EXPECT--
