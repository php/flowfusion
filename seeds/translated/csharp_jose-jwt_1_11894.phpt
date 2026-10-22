--TEST--
Translated seed (inferredbugs-csharp: jose-jwt/1)
--FILE--
<?php
$k = 'a0a2abd8-6162-41c3-83d6-1cf559b46afc';
$t = 'eyJhbGciOiJub25lIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.';
$p = explode('.', $t);
$j = base64_decode(strtr($p[1], '-_', '+/'));
var_dump(json_decode($j, true));
$h = ['typ' => 'JWT', 'alg' => 'none'];
$token = rtrim(strtr(base64_encode(json_encode($h, JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=') . '.' . rtrim(strtr(base64_encode(json_encode(['hello' => 'world'], JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=') . '.';
echo $token, PHP_EOL;
var_dump(explode('.', $token));
$payload = '{"hello": "world"}';
echo $payload, PHP_EOL;
echo strlen($k) . PHP_EOL;
?>
--EXPECT--
