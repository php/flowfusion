--TEST--
Translated seed (inferredbugs-java: commons-codec/18)
--FILE--
<?php

class Base64
{
    public static function isBase64($data) {
        if (is_null($data)) {
            throw new Exception("Input cannot be null");
        }

        return preg_match('/^(?:[A-Z0-9+\/=]*[A-Z0-9+\/])?=?(?:[A-Z0-9+\/])*$/', $data);
    }
    
    public static function encode($data) {
        return base64_encode($data);
    }
    
    public static function decode($data) {
        return base64_decode($data);
    }
}

function testBase64() {
    $nullString = null;
    $emptyString = "";
    $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
    $invalidString = $validString . "\0"; // append null character
    
    try {
        Base64::isBase64($nullString);
    } catch (Exception $e) {
        // Exception expected
    }
    
    if (!Base64::isBase64($emptyString) || !Base64::isBase64($validString)) {
        exit(1); // Test failed
    }
    
    if (Base64::isBase64($invalidString)) {
        exit(1); // Test failed
    }
    
    $content = "Hello World";
    $encodedContent = Base64::encode($content);
    if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
        exit(1); // Test failed
    }

    $decodedContent = Base64::decode($encodedContent);
    if ($decodedContent !== $content) {
        exit(1); // Test failed
    }
}

testBase64();
--EXPECT--
