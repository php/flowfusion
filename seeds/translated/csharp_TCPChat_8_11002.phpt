--TEST--
Translated seed (inferredbugs-csharp: TCPChat/8)
--FILE--
<?php
$key = random_bytes(16);
$iv = random_bytes(16);
$data = str_repeat("A", 12345);
$cipher = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
$plain = openssl_decrypt($cipher, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
if ($plain !== $data) {
    throw new Exception("decryption failed");
}
$buf = fopen('php://temp', 'w+b');
fwrite($buf, pack('P', strlen($data)) . $iv . $cipher);
rewind($buf);
$header = fread($buf, 8);
$readIv = fread($buf, 16);
$payload = stream_get_contents($buf);
$decoded = openssl_decrypt($payload, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $readIv);
if ($decoded !== $data) {
    throw new Exception("stream roundtrip failed");
}
?>
--EXPECT--
