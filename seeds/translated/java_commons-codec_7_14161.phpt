--TEST--
Translated seed (inferredbugs-java: commons-codec/7)
--FILE--
<?php

function base64_encode_custom($data) {
    return base64_encode($data);
}

function base64_decode_custom($data) {
    return base64_decode($data);
}

$content = "Hello World";
$encodedContent = base64_encode_custom($content);
$decodedContent = base64_decode_custom($encodedContent);

$invalidBase64 = "SGVsbG{}8gV29ybGQ=";
$decodedInvalid = base64_decode_custom($invalidBase64);

$values = [
    new BigInteger("857393771208094202104259627990318636601332086981"),
    new BigInteger("139367275728611672546664672689146679477132949611"),
];

foreach ($values as $value) {
    $encodedValue = base64_encode_custom($value);
    $decodedValue = base64_decode_custom($encodedValue);
}

$empty = "";
$encodedEmpty = base64_encode_custom($empty);
$decodedEmpty = base64_decode_custom($encodedEmpty);

$spaceEncoded = base64_encode_custom(" I am a late night coder. ");
$spaceDecoded = base64_decode_custom($spaceEncoded);

$randomData = random_bytes(100);
$encodedRandom = base64_encode_custom($randomData);
$decodedRandom = base64_decode_custom($encodedRandom);

$testStrings = [
    "Hello World",
    "foobar",
    "",
    "SGVsbG8gV29ybGQ=",
];

foreach ($testStrings as $string) {
    $encoded = base64_encode_custom($string);
    $decoded = base64_decode_custom($encoded);
}
--EXPECT--
