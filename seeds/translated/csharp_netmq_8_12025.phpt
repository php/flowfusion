--TEST--
Translated seed (inferredbugs-csharp: netmq/8)
--FILE--
<?php
$text = "hellohello-hello1";
$key = "1";
$iv = str_repeat("\0", 16);
$cipher = "AES-128-CBC";

$encrypted = openssl_encrypt($text, $cipher, $key, OPENSSL_RAW_DATA, $iv);
$decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);

echo $decrypted;
?>
--EXPECT--
