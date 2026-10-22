--TEST--
Translated seed (inferredbugs-java: commons-codec/8)
--FILE--
<?php

class Base64 {
    const CHUNK_SEPARATOR = "\r\n";

    public static function encode($data) {
        return base64_encode($data);
    }

    public static function decode($data) {
        return base64_decode($data);
    }
}

$content = "Hello World";
$encodedContent = Base64::encode($content);
$decodedContent = Base64::decode($encodedContent);

$invalidBase64 = "SGVsbG{}8gV29ybGQ=";
$decodeInvalid = Base64::decode($invalidBase64);

$edgeCaseData = new BigInteger("857393771208094202104259627990318636601332086986981");
$encodedInt1 = Base64::encode(pack('G', $edgeCaseData->toString()));
$decodedInt1 = Base64::decode($encodedInt1);

$zeroLengthArray = '';
$encodedEmpty = Base64::encode($zeroLengthArray);
$decodedEmpty = Base64::decode($encodedEmpty);
--EXPECT--
