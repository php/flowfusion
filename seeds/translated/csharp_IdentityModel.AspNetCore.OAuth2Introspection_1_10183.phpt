--TEST--
Translated seed (inferredbugs-csharp: IdentityModel.AspNetCore.OAuth2Introspection/1)
--FILE--
<?php
$u = 'https://authority.com/.well-known/openid-configuration';
if (($p = parse_url($u)) && ($p['scheme'] ?? '') === 'https' && ($p['host'] ?? '') === 'authority.com' && ($p['path'] ?? '') === '/.well-known/openid-configuration') {
    $endpoint = $u;
    $data = [
        'issuer' => 'https://authority.com',
        'introspection_endpoint' => 'https://authority.com/introspection_endpoint',
    ];
    $json = json_encode($data, JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        exit(1);
    }
    echo $json;
} else {
    http_response_code(404);
}
?>
--EXPECT--
