--TEST--
Translated seed (inferredbugs-java: blade-tool/3)
--FILE--
<?php
$BASE64_SECURITY = base64_encode("SpringBlade");
$HEADER = "blade-auth";
$BEARER = "bearer";
$AUTH_LENGTH = 7;

function getHeader() {
    return $_SERVER[$GLOBALS['HEADER']] ?? null;
}

function getClaims() {
    $auth = getHeader();
    if ($auth && strlen($auth) > $GLOBALS['AUTH_LENGTH']) {
        $headStr = strtolower(substr($auth, 0, 6));
        if ($headStr === $GLOBALS['BEARER']) {
            $auth = substr($auth, 7);
            return parseJWT($auth);
        }
    }
    return null;
}

function parseJWT($jsonWebToken) {
    // Simulate parsing JWT and return claims
    return ['userId' => 123, 'roleId' => 'role_1', 'account' => 'user@example.com', 'roleName' => 'admin'];
}

function getUserId() {
    $claims = getClaims();
    return $claims ? (int) $claims['userId'] : -1;
}

function getUserAccount() {
    $claims = getClaims();
    return $claims ? $claims['account'] : '';
}

$userId = getUserId();
$userAccount = getUserAccount();

echo "User ID: $userId\n";
echo "User Account: $userAccount\n";
--EXPECT--
