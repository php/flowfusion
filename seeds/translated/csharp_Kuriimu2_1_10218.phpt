--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/1)
--FILE--
<?php
$k = str_repeat("\0", 16);
$c = hex2bin('66e94bd4ef8a2c3b884cfa59ca342b2e');
$e = openssl_encrypt($c, 'aes-128-ecb', $k, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
$m = fopen('php://memory', 'r+b');
fwrite($m, $c);
fseek($m, 3);
fwrite($m, "\x11\x11");
fflush($m);
fseek($m, 0);
fread($m, 16);
$m2 = fopen('php://memory', 'r+b');
fwrite($m2, $c);
fseek($m2, 15);
fwrite($m2, "\x11\x11");
fflush($m2);
fseek($m2, 0);
fread($m2, 32);
$xts = str_repeat("\0", 16);
$sector = str_repeat("\0", 16);
$buf = str_repeat("\0", 16);
openssl_encrypt($buf, 'aes-128-xts', str_repeat("\0", 32), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $sector);
openssl_decrypt($buf, 'aes-128-xts', str_repeat("\0", 32), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $sector);
?>
--EXPECT--
