--TEST--
Translated seed (inferredbugs-csharp: jose-jwt/3)
--FILE--
<?php
$token = "eyJhbGciOiJub25lIn0.eyJoZWxsbyI6ICJ3b3JsZCJ9.";
$parts = explode('.', $token);
$json = base64_decode(strtr($parts[1], '-_', '+/'));
$data = json_decode($json, true);
echo json_encode($data, JSON_UNESCAPED_SLASHES), PHP_EOL;

$payload = ['hello' => 'world'];
$header = ['typ' => 'JWT', 'alg' => 'none'];
$hashed = rtrim(strtr(base64_encode(json_encode($header, JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=') . '.' .
          rtrim(strtr(base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=') . '.';
echo $hashed, PHP_EOL;
?>
--EXPECT--
