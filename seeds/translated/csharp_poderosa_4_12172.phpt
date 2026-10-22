--TEST--
Translated seed (inferredbugs-csharp: poderosa/4)
--FILE--
<?php
$h = random_bytes(9);
$s = random_bytes(9);
if ($h[0] === "\0") $h = substr($h, 1);
if ($s[0] === "\0") $s = substr($s, 1);
$cookie = random_bytes(8);
$sessionId = md5($h . $s . $cookie, true);
$key = random_bytes(32);
for ($i = 0; $i < 16; $i++) $key[$i] = $key[$i] ^ $sessionId[$i];
$payload = $key . $sessionId . $cookie;
$hash = md5($payload, true);
echo bin2hex($hash), PHP_EOL;
--EXPECT--
