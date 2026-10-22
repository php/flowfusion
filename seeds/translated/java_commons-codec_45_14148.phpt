--TEST--
Translated seed (inferredbugs-java: commons-codec/45)
--FILE--
<?php

$encodedB64 = "AAAA////";
$stringFixture = "Hello World";

$encoded = base64_decode($encodedB64);
$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encoded);
rewind($inputStream);

fseek($inputStream, 1);
$decodedBytes = stream_get_contents($inputStream);
fclose($inputStream);

$decodedString = $decodedBytes !== false ? $decodedBytes : '';
echo substr($decodedString, 1) === substr($stringFixture, 1) ? 'Test passed' : 'Test failed';

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encodedB64);
rewind($inputStream);
$base64Stream = fopen('php://temp', 'r+');

while (($char = fgetc($inputStream)) !== false) {
    fwrite($base64Stream, $char);
}
rewind($base64Stream);
$contents = stream_get_contents($base64Stream);
fclose($base64Stream);
fclose($inputStream);

$bytesRead = strlen($contents);
if ($bytesRead > 0) {
    echo "Read successful: $bytesRead bytes\n";
} else {
    echo "Read failed or end of stream\n";
}

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, "");
rewind($inputStream);
$endStreamRead = fread($inputStream, 1);
fclose($inputStream);
echo ($endStreamRead === false) ? "EOF reached\n" : "Data available\n";

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encodedB64);
rewind($inputStream);
$available = stream_get_meta_data($inputStream)['unread_bytes'];
echo "Available bytes before skip: $available\n";

$skippedBytes = fscanf($inputStream, "%s");
rewind($inputStream);
$availableAfterSkip = stream_get_meta_data($inputStream)['unread_bytes'];
echo "Available bytes after skip: $availableAfterSkip\n";

$skippedBytes = fseek($inputStream, 10, SEEK_SET);
fclose($inputStream);

$inputStream = fopen('php://memory', 'r+');
fwrite($inputStream, $encodedB64);
rewind($inputStream);
$read = fread($inputStream, 6);
fclose($inputStream);
echo "Read data: " . implode(',', unpack('C*', $read)) . "\n";
--EXPECT--
