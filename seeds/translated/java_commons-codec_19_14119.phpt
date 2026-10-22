--TEST--
Translated seed (inferredbugs-java: commons-codec/19)
--FILE--
<?php

class Base64 {
    public static function isBase64($data) {
        if ($data === null) throw new \Exception("Null not allowed");
        $decoded = base64_decode($data, true);
        if ($decoded === false) return false;
        return base64_encode($decoded) === $data;
    }

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data);
    }
}

function runTests() {
    $nullString = null;
    $emptyString = "";
    $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
    $invalidString = $validString . "\0"; // append null character

    try {
        Base64::isBase64($nullString);
    } catch (\Exception $e) {
        // Expected: Null not allowed
    }

    echo Base64::isBase64($emptyString) ? "true\n" : "false\n"; 
    echo Base64::isBase64($validString) ? "true\n" : "false\n"; 
    echo Base64::isBase64($invalidString) ? "true\n" : "false\n"; 

    $content = "Hello World";
    $encodedContent = Base64::encode($content);
    echo ($encodedContent === "SGVsbG8gV29ybGQ=") ? "true\n" : "false\n"; 

    $decodedContent = Base64::decode($encodedContent);
    echo ($decodedContent === $content) ? "true\n" : "false\n"; 

    // Testing code that triggers exception for invalid base64
    $invalidBytes = ['n', 'A', '=', '=', chr(0x9c)];
    try {
        Base64::decode(implode('', $invalidBytes));
    } catch (\Exception $e) {
        // Expected: Exception for invalid Base64 input
    }
}

runTests();

?>
--EXPECT--
