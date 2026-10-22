--TEST--
Translated seed (inferredbugs-java: commons-codec/55)
--FILE--
<?php

function isBase64($string) {
    return preg_match('/^[A-Za-z0-9\+\/]*={0,2}$/', $string) && strlen($string) % 4 == 0;
}

$content = "SGVsbG8gV29ybGQ=SGVsbG8gV29ybGQ=";
$result = base64_decode($content);
$shouldBe = "Hello World";
if ($result !== $shouldBe) {
    exit(1);
}

$invalidString = $content . "\0"; 
if (isBase64($invalidString)) {
    exit(1);
}

$emptyString = "";
if (!isBase64($emptyString)) {
    exit(1);
}

$validString = "abc===defg\n\r123456\r789\r\rABC\n\nDEF==GHI\r\nJKL==============";
if (!isBase64($validString)) {
    exit(1);
}

$data = 'Hello World';
$encoded = base64_encode($data);
if ($encoded !== "SGVsbG8gV29ybGQ=") {
    exit(1);
}

$randomData = random_bytes(10);
$encodedRandom = base64_encode($randomData);
$decodedRandom = base64_decode($encodedRandom);
if ($randomData !== $decodedRandom) {
    exit(1);
}

for ($i = 0; $i < 12; $i++) {
    $smallData = random_bytes($i);
    $encodedSmall = base64_encode($smallData);
    $decodedSmall = base64_decode($encodedSmall);
    if ($smallData !== $decodedSmall) {
        exit(1);
    }
}

$nullData = null;
if (base64_encode($nullData) !== false) {
    exit(1);
}
--EXPECT--
