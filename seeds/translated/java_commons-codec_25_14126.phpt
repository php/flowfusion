--TEST--
Translated seed (inferredbugs-java: commons-codec/25)
--FILE--
<?php

class Base64 {
    public static function isBase64($data) {
        return preg_match('/^[A-Za-z0-9\/\+=]+$/', $data);
    }

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data, true);
    }
}

$nullString = null;
$emptyString = "";
$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
$invalidString = $validString . "\x00"; // append null byte

try {
    Base64::isBase64($nullString);
} catch (TypeError $e) {
    // Catch TypeError for passing null to a function that expects a string
}

$results = [];
$results[] = Base64::isBase64($emptyString);
$results[] = Base64::isBase64($validString);
$results[] = !Base64::isBase64($invalidString);

$content = "Hello World";
$encodedContent = Base64::encode($content);
$expectedEncodedContent = "SGVsbG8gV29ybGQ=";
$results[] = $encodedContent === $expectedEncodedContent;

$decodedString = Base64::decode($expectedEncodedContent);
$results[] = $decodedString === $content;

$bogusString = "SGVsbG{\u00e9\u00e9\u00e9\x8cgV29ybGQ=";
$decodedBogus = Base64::decode($bogusString);
$results[] = $decodedBogus === false; // Should return false for invalid base64

echo implode("\n", $results);
--EXPECT--
