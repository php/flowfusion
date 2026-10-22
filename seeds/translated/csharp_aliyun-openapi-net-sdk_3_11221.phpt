--TEST--
Translated seed (inferredbugs-csharp: aliyun-openapi-net-sdk/3)
--FILE--
<?php
$secret = "secret";
$stringToSign = "test";
echo base64_encode(hash_hmac("sha1", $stringToSign, $secret, true)), PHP_EOL;
echo "HMAC-SHA1", PHP_EOL;
echo "1.0", PHP_EOL;
echo "", PHP_EOL;
?>
--EXPECT--
