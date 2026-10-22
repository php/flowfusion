--TEST--
Translated seed (inferredbugs-java: commons-codec/54)
--FILE--
<?php
function isBase64($string) {
    return preg_match('@^[A-Za-z0-9+/]*={0,2}$@', $string) && (base64_decode($string, true) !== false);
}

function decodeBase64($data) {
    return base64_decode($data, true);
}

$nullString = null;
$emptyString = "";
$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
$invalidString = $validString . "\0";

try {
    isBase64($nullString);
} catch (Exception $e) {
}

isBase64($emptyString);
isBase64($validString);
$is_base64_invalid = isBase64($invalidString);

$content = "Hello World";
$encodedContent = base64_encode($content);
$expected = "SGVsbG8gV29ybGQ=";
$decodedContent = base64_decode($encodedContent);
$decodeResult = ($decodedContent === $content);

$resultWithInnerPad = decodeBase64("SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=");
$shouldBe = "Hello World";
$decodeHalt = ($resultWithInnerPad === $shouldBe);
--EXPECT--
