--TEST--
Translated seed (inferredbugs-java: commons-codec/49)
--FILE--
<?php

class Base64 {
    public static function isBase64($data) {
        if (is_null($data)) {
            throw new Exception("Input cannot be null");
        }
        $data = str_replace(["\r", "\n"], '', $data);
        return preg_match('/^[a-zA-Z0-9\/\+=]*$/', $data);
    }

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data, true);
    }

    public static function test() {
        $nullString = null;
        $emptyString = "";
        $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
        $invalidString = $validString . chr(0); // append null character

        try {
            self::isBase64($nullString);
        } catch (Exception $e) {
            // Expected
        }

        echo self::isBase64($emptyString) ? "true\n" : "false\n";
        echo self::isBase64($validString) ? "true\n" : "false\n";
        echo !self::isBase64($invalidString) ? "true\n" : "false\n";

        $content = "Hello World";
        $encodedContent = self::encode($content);
        echo $encodedContent === "SGVsbG8gV29ybGQ=" ? "Encoding works\n" : "Encoding failed\n";

        $decodedContent = self::decode($encodedContent);
        echo $decodedContent === $content ? "Decoding works\n" : "Decoding failed\n";

        $innerPadString = "SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=";
        $result = self::decode($innerPadString);
        echo $result === $content ? "Decode halts at pad\n" : "Decode does not halt\n";
    }
}

Base64::test();
--EXPECT--
