--TEST--
Translated seed (inferredbugs-java: commons-codec/53)
--FILE--
<?php

class Base64Test {

    public static function main() {
        $nullString = null;
        $emptyString = "";
        $validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
        $invalidString = $validString . "\0"; // append null character

        try {
            self::isBase64($nullString);
        } catch (Exception $npe) {
            // expected
        }

        echo self::isBase64($emptyString) ? "true\n" : "false\n";
        echo self::isBase64($validString) ? "true\n" : "false\n";
        echo !self::isBase64($invalidString) ? "true\n" : "false\n";

        $content = "Hello World";
        $encodedContent = self::base64Encode($content);
        echo $encodedContent === "SGVsbG8gV29ybGQ=" ? "true\n" : "false\n";

        $result = self::base64Decode("SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=");
        echo $result === "Hello World" ? "true\n" : "false\n";

        $invalidBase64 = "SGVsbG{\u00e9\u00e9\u00e9\u00e9\u00e9\u00e9}8gV29ybGQ=";
        $decoded = self::base64DecodeIgnoreInvalid($invalidBase64);
        echo $decoded === "Hello World" ? "true\n" : "false\n";
    }

    private static function isBase64($data) {
        if (is_null($data)) throw new Exception();
        return base64_encode(base64_decode($data, true)) === $data;
    }

    private static function base64Encode($data) {
        return base64_encode($data);
    }

    private static function base64Decode($data) {
        return base64_decode($data);
    }

    private static function base64DecodeIgnoreInvalid($data) {
        return base64_decode($data, true);
    }
}

Base64Test::main();
--EXPECT--
