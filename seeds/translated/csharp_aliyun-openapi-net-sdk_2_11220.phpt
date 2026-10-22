--TEST--
Translated seed (inferredbugs-csharp: aliyun-openapi-net-sdk/2)
--FILE--
<?php
$s = "test";
$k = "secret";
$hash = hash("sha256", $s, true);
$pair = openssl_pkey_new(["private_key_bits" => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA]);
openssl_sign($s, $sig, $pair, OPENSSL_ALGO_SHA256);
echo base64_encode($sig), "\n";
echo "SHA256withRSA", "\n";
echo "1.0", "\n";
echo "PRIVATEKEY", "\n";
?>
--EXPECT--
