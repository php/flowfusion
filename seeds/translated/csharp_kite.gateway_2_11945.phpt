--TEST--
Translated seed (inferredbugs-csharp: kite.gateway/2)
--FILE--
<?php
$useSsl = false;
$issuer = "issuer";
$audience = "audience";
$expiresTime = 1;
$claims = ["sub" => "user", "role" => "admin"];

$header = ["alg" => $useSsl ? "RS256" : "HS256", "typ" => "JWT"];
$payload = $claims + [
    "iss" => $issuer,
    "aud" => $audience,
    "iat" => time(),
    "exp" => time() + $expiresTime,
];

$base64url = function ($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
};

$segments = [
    $base64url(json_encode($header, JSON_UNESCAPED_SLASHES)),
    $base64url(json_encode($payload, JSON_UNESCAPED_SLASHES)),
];

if ($useSsl) {
    $certPath = __DIR__ . DIRECTORY_SEPARATOR . "cert.pem";
    if (!is_file($certPath)) {
        throw new InvalidArgumentException("certificate file missing: " . $certPath);
    }
    $privateKey = openssl_pkey_get_private(file_get_contents($certPath), "");
    $signature = "";
    openssl_sign(implode(".", $segments), $signature, $privateKey, OPENSSL_ALGO_SHA256);
    $segments[] = $base64url($signature);
} else {
    $secret = "secret-key";
    $segments[] = $base64url(hash_hmac("sha256", implode(".", $segments), $secret, true));
}

$jwt = implode(".", $segments);
$result = [
    "AccessToken" => $jwt,
    "EffectiveTime" => $expiresTime,
    "IssueTime" => time(),
    "ExpiresTime" => time() + $expiresTime,
];

echo $result["AccessToken"] . PHP_EOL;
?>
--EXPECT--
