--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net/3)
--FILE--
<?php
$stream = fopen('php://temp', 'r+');
fwrite($stream, "abcdefghijklmnopqrstuvwxyz");
rewind($stream);

$offset = 5;
$length = 10;
$md5 = base64_encode(hex2bin('d41d8cd98f00b204e9800998ecf8427e'));
$crc64 = '1234567890abcdef';

fseek($stream, $offset, SEEK_SET);
$data = stream_get_contents($stream, $length);

$headers = [];
$headers['Content-Length'] = $length;
$headers['Content-MD5'] = base64_decode($md5, true);
$headers['Content-Crc64'] = $crc64;

echo $data;
?>
--EXPECT--
