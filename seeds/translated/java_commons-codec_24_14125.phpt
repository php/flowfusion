--TEST--
Translated seed (inferredbugs-java: commons-codec/24)
--FILE--
<?php

class Base64 {
    public static function isBase64($data) {
        if ($data === null) {
            throw new Exception("Input cannot be null");
        }
        return preg_match('/^(?:[A-Z0-9+\/=]+)$/', $data) > 0;
    }

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data, true);
    }
}

function testIsStringBase64() {
    $nullString = null;
    $emptyString = "";
    $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
    $invalidString = $validString . chr(0);

    try {
        Base64::isBase64($nullString);
    } catch (Exception $e) {
        // Expected: Input cannot be null
    }

    if (!Base64::isBase64($emptyString) || !Base64::isBase64($validString) || Base64::isBase64($invalidString) ) {
        throw new Exception("Test failed");
    }
}

function testBase64() {
    $content = "Hello World";
    $encodedContent = Base64::encode($content);
    if ($encodedContent !== "SGVsbG8gV29ybGQ=") {
        throw new Exception("Encoding failed");
    }

    $decodedContent = Base64::decode($encodedContent);
    if ($decodedContent !== $content) {
        throw new Exception("Decoding failed");
    }
}

function testCodec68() {
    $data = pack('C*', 'n', 'A', '=', '=', 0x9c);
    Base64::decode($data);
}

function runTests() {
    testIsStringBase64();
    testBase64();
    testCodec68();
}

runTests();
--EXPECT--
