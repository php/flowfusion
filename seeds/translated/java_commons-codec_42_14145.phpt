--TEST--
Translated seed (inferredbugs-java: commons-codec/42)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];
$stringFixture = "Hello World";

$ins = fopen('php://memory', 'r+');
fwrite($ins, base64_decode("SGVsbG8gV29ybGQ="));
rewind($ins);
$decodedStream = fopen('php://temp', 'r+');
$encodedStream = fopen('php://temp', 'r+');

fwrite($encodedStream, "SGVsbG8gV29ybGQ=");
rewind($encodedStream);
$buffer = fread($encodedStream, 8192);
$firstRead = strlen($buffer);
if ($firstRead > 0) {
    $buffer = fread($encodedStream, 0);
}
$secondRead = strlen($buffer);

fwrite($ins, $encodedB64);
rewind($ins);
$length = stream_get_contents($ins, -1, 0);
$skipped = stream_get_contents($ins, 6);
$finalRead = fread($ins, 1);
$endRead = fread($ins, 1);
fclose($ins);
fclose($encodedStream);
fclose($decodedStream);
--EXPECT--
