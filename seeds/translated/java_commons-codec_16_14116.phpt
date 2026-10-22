--TEST--
Translated seed (inferredbugs-java: commons-codec/16)
--FILE--
<?php

$encodedB64 = "AAAA////";
$expectedBytes = [0, 0, 0, 255, 255, 255];

$inputStream = fopen('data://text/plain;base64,' . base64_encode($encodedB64), 'r');
$decoded = [];

$base64Stream = fopen('php://temp', 'r+');
stream_copy_to_stream($inputStream, $base64Stream);
rewind($base64Stream);

// Skip the first byte
fgetc($base64Stream);

// Read the remaining bytes
while (($byte = fgetc($base64Stream)) !== false) {
    $decoded[] = ord($byte);
}

fclose($inputStream);
fclose($base64Stream);

if ($decoded === $expectedBytes) {
    exit(0);
} else {
    exit(1);
}
--EXPECT--
