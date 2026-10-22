--TEST--
Translated seed (inferredbugs-java: blade-tool/1)
--FILE--
<?php

header('Content-Type: application/json');

$header = "Authorization";
$bearer = "bearer";
$userIdKey = "userId";
$roleIdKey = "roleId";
$accountKey = "account";
$roleNameKey = "roleName";
$base64Security = base64_encode("SpringBlade");

function getClaims($headers) {
    global $header, $bearer;
    if (isset($headers[$header]) && strlen($headers[$header]) > 7) {
        $auth = $headers[$header];
        $headStr = strtolower(substr($auth, 0, 6));
        if ($headStr === $bearer) {
            $jwt = substr($auth, 7);
            return parseJWT($jwt);
        }
    }
    return null;
}

function parseJWT($jwt) {
    global $base64Security;
    try {
        list($header, $payload, $signature) = explode('.', $jwt);
        $decodedPayload = json_decode(base64_decode($payload), true);
        return $decodedPayload;
    } catch (Exception $ex) {
        return null;
    }
}

function createJWT($user, $audience, $issuer, $isExpire) {
    global $base64Security;
    $header = json_encode(["typ" => "JsonWebToken"]);
    $payload = json_encode($user);
    $signature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, base64_decode($base64Security), true));
    return base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
}

$userHeader = [
    "Authorization" => "bearer " . createJWT([
        $userIdKey => 1,
        $roleIdKey => "admin",
        $accountKey => "test_user",
        $roleNameKey => "administrator"
    ], "audience", "issuer", true)
];

$claims = getClaims($userHeader);
if ($claims) {
    echo json_encode($claims);
}
--EXPECT--
