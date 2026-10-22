--TEST--
Translated seed (inferredbugs-java: commons-codec/15)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];

$base64Stream = base64_decode($encodedB64, true);
$actualBytes = [];
for ($i = 0; $i < strlen($base64Stream); $i++) {
    $actualBytes[] = ord($base64Stream[$i]);
}

if (array_slice($actualBytes, 1) === array_slice($decodedBytes, 1)) {
    echo "Test passed.\n";
} else {
    echo "Test failed.\n";
}
--EXPECT--
