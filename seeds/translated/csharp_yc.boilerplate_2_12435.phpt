--TEST--
Translated seed (inferredbugs-csharp: yc.boilerplate/2)
--FILE--
<?php
$req = (object)[
    'method' => 'GET',
    'path' => '/api/Identity/RefreshToken',
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Chrome/124.0.0.0 Mobile Safari/537.36',
        'Authorization' => 'Bearer tok123',
    ],
];

$cache = [
    'token_user_tok123' => (object)['TenantId' => 1, 'Id' => 42, 'Account' => 'alice'],
    'perm_tenantId_1_userId_42' => (object)['PermissionList' => [(object)['Api' => 'Identity/GetTokenByLogin'], (object)['Api' => 'Identity/RefreshToken']]],
];

$ua = $req->headers['User-Agent'];
$browser = preg_match('/Chrome\/([\d.]+)/', $ua, $m) ? 'Chrome' : 'Other';
$os = preg_match('/Android\s+([0-9.]+)/', $ua, $m) ? 'Android' : 'Other';
$device = stripos($ua, 'Mobile') !== false ? 'Mobile' : '';
$start = microtime(true);

$token = trim(str_replace('Bearer', '', $req->headers['Authorization']));
if ($token === '') {
    exit;
}

$user = $cache['token_user_' . $token] ?? null;
if (!$user) {
    exit;
}

$perm = $cache['perm_tenantId_' . $user->TenantId . '_userId_' . $user->Id] ?? null;
if ($perm === null) {
    exit;
}

$requestApi = str_replace('/api/', '', $req->path);
$allowed = false;
foreach ($perm->PermissionList as $p) {
    if (strpos($p->Api, $requestApi) !== false) {
        $allowed = true;
        break;
    }
}
if (!$allowed && strpos('Identity/RefreshToken', $requestApi) === false) {
    exit;
}

$result = (object)['State' => true, 'Value' => 'ok'];
$responseState = false;
if (is_object($result) && property_exists($result, 'State')) {
    $responseState = (bool)$result->State;
}

$elapsed = (int)round((microtime(true) - $start) * 1000);
echo $browser . "\n" . $os . "\n" . $device . "\n" . ($responseState ? '1' : '0') . "\n" . $elapsed . "\n";
--EXPECT--
