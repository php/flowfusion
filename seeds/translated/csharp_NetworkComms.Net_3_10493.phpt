--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/3)
--FILE--
<?php
$password = "password";
$salt = "\x76\x64\x7b\x88\x14\xf2\xaa\xe3\x61\xa8\x65\xb1\xd6\xd3\x76\x89";
$plain = str_repeat("A", 1024);

$key = hash_pbkdf2("sha256", $password, $salt, 1000, 32, true);
$iv = hash_pbkdf2("sha256", $password, $salt, 1000, 16, true);

$cipher = openssl_encrypt($plain, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
$decrypted = openssl_decrypt($cipher, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);

echo strlen($cipher) . "\n";
echo strlen($decrypted) . "\n";
?>
--EXPECT--
