--TEST--
Translated seed (inferredbugs-java: commons-codec/22)
--FILE--
<?php

class Base64 {
    public static function isBase64($data) {
        return preg_match('/^(?:[A-Za-z0-9+\/]{4})*(?:(?:[A-Za-z0-9+\/]{2}==)|(?:[A-Za-z0-9+\/]{3}=))?$/', $data);
    }

    public static function encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function decode($data) {
        $data .= str_repeat('=', (4 - strlen($data) % 4) % 4);
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

$nullString = null;
$emptyString = "";
$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
$invalidString = $validString . chr(0);

try {
    Base64::isBase64($nullString);
} catch (Exception $e) {
    // expected Null handling, continue
}

$validBase64Empty = Base64::isBase64($emptyString);
$validBase64String = Base64::isBase64($validString);
$invalidBase64String = Base64::isBase64($invalidString);

$content = "Hello World";
$encodedContent = Base64::encode($content);
$decodedContent = Base64::decode($encodedContent);

$chunkedEncode = Base64::encode($content);
$chunkedContent = Base64::decode($chunkedEncode);
$decodeInvalid = Base64::decode("SGVsbG{\u00e9\u00e9\u00e9\u00e9\u00e9\u00e9}8gV29ybGQ=");
$decodePadOnly = Base64::decode("====");
$decodeWithWhitespace = Base64::decode("I am a late night coder.");

$randomData = random_bytes(100);
$encodedRandom = Base64::encode($randomData);
$decodedRandom = Base64::decode($encodedRandom);

$encodedNull = Base64::encode(null);
$encodedEmpty = Base64::encode("");
$decodedEmpty = Base64::decode("");
--EXPECT--
