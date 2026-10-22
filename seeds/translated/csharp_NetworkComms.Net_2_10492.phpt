--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/2)
--FILE--
<?php
$passwordOption = 'RijndaelPSKEncrypter_PASSWORD';
$salt = hex2bin('76647b8814f2aae361a865b1d6d37689');
$pw = 'password';
$in = "bug-pattern";
$key = hash_pbkdf2('sha256', $pw, $salt, 1000, 32, true);
$iv = hash_pbkdf2('sha256', $pw, $salt, 1000, 16, true);
$out = openssl_encrypt($in, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
$plain = openssl_decrypt($out, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
echo $plain;
--EXPECT--
