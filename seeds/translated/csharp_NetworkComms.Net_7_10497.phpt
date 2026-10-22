--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/7)
--FILE--
<?php
$bytes = random_bytes(1024 * 1024 + 17);
$chunkSize = 4096;
$total = intdiv(strlen($bytes) + $chunkSize - 1, $chunkSize);
$chunks = [];
for ($i = 0; $i < $total; $i++) {
    $chunks[$i] = substr($bytes, $i * $chunkSize, $chunkSize);
}
$reassembled = '';
for ($i = 0; $i < $total; $i++) {
    $reassembled .= $chunks[$i];
}
if (md5($reassembled, false) !== md5($bytes, false)) {
    throw new Exception('checksum mismatch');
}
$fp = fopen('php://temp', 'w+b');
for ($i = 0; $i < $total; $i++) {
    fseek($fp, $i * $chunkSize);
    fwrite($fp, $chunks[$i]);
}
rewind($fp);
$data = stream_get_contents($fp);
fclose($fp);
if ($data !== $bytes) {
    throw new Exception('stream mismatch');
}
echo strlen($data), "\n";
--EXPECT--
