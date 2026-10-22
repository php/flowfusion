--TEST--
Translated seed (inferredbugs-java: commons-codec/39)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];
$inputStream = fopen("php://memory", "r+");
fwrite($inputStream, base64_decode($encodedB64));
rewind($inputStream);
$bytesRead = fread($inputStream, 1);
fseek($inputStream, 0, SEEK_SET);
$bytesRead = fread($inputStream, 6);
$actualDecoded = array_values(unpack("C*", $bytesRead));
if ($actualDecoded !== $decodedBytes) {
    die("Bug in decoding");
}
fclose($inputStream);
--EXPECT--
