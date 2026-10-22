--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/1)
--FILE--
<?php
$passwordOption = 'RijndaelPSKEncrypter_PASSWORD';
$salt = "\x76\x64\x7b\x88\x14\xf2\xaa\xe3\x61\xa8\x65\xb1\xd6\xd3\x76\x89";
$pwd = $argv[1] ?? 'password';
$data = str_repeat("The quick brown fox jumps over the lazy dog.\n", 3);
$key = hash_pbkdf2('sha256', $pwd, $salt, 1000, 48, true);
$enc = openssl_encrypt($data, 'aes-256-cbc', substr($key, 0, 32), OPENSSL_RAW_DATA, substr($key, 32, 16));
$dec = openssl_decrypt($enc, 'aes-256-cbc', substr($key, 0, 32), OPENSSL_RAW_DATA, substr($key, 32, 16));
if ($dec !== $data) {
    exit(1);
}
echo $dec;
?>
--EXPECT--
