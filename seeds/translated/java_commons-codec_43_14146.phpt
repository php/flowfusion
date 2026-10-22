--TEST--
Translated seed (inferredbugs-java: commons-codec/43)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];
$inputStream = fopen("php://temp", "r+");
fwrite($inputStream, base64_decode($encodedB64));
rewind($inputStream);

$skipBytes = 1;
fread($inputStream, $skipBytes); // Skip first byte
$decodedData = fread($inputStream, 6);
$decodedArray = array_values(unpack("C*", $decodedData));

if ($decodedArray !== array_slice($decodedBytes, 1)) {
    throw new Exception("Decoded data does not match expected output.");
}

fclose($inputStream);
--EXPECT--
