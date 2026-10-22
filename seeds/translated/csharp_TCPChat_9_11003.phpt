--TEST--
Translated seed (inferredbugs-csharp: TCPChat/9)
--FILE--
<?php
$plain = str_repeat("A", 1000);
$key = random_bytes(32);
$iv = random_bytes(16);
$cipher = "aes-256-cbc";
$enc = openssl_encrypt($plain, $cipher, $key, OPENSSL_RAW_DATA, $iv);
$head = pack("P", strlen($plain));
$blob = $head . $iv . $enc;
$len = unpack("P", substr($blob, 0, 8))[1];
$iv2 = substr($blob, 8, 16);
$data = substr($blob, 24);
$dec = openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv2);
if ($dec !== false && strlen($dec) >= $len) {
    $dec = substr($dec, 0, $len);
}
echo $dec;
?>
--EXPECT--
