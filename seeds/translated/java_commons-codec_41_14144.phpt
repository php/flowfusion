--TEST--
Translated seed (inferredbugs-java: commons-codec/41)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];
$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, base64_decode($encodedB64));
rewind($inputStream);

$bytesToSkip = 1;
fseek($inputStream, $bytesToSkip, SEEK_CUR); // Skip first byte
$decodedData = stream_get_contents($inputStream);
$decodedArray = array_values(unpack("C*", $decodedData));

if ($decodedArray === array_slice($decodedBytes, $bytesToSkip)) {
    echo "Decoded bytes match expected output.";
} else {
    echo "Decoded bytes do not match expected output.";
}
fclose($inputStream);
--EXPECT--
