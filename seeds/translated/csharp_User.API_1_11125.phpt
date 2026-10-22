--TEST--
Translated seed (inferredbugs-csharp: User.API/1)
--FILE--
<?php
$clients = [
    ['client_id' => 'a', 'grant_types' => ['sms_auth_code'], 'redirect_uris' => []],
    ['client_id' => 'b', 'grant_types' => ['client_credentials'], 'redirect_uris' => ['https://example.test/callback']],
];
$identityResources = ['openid', 'profile', 'email'];
$apiResources = [
    ['name' => 'api1', 'scopes' => ['read', 'write']],
    ['name' => 'api2', 'scopes' => []],
];

$services = [];
$services['http_client'] = new stdClass();
$services['auth_code_service'] = new stdClass();
$services['user_service'] = new stdClass();
$services['identity_server'] = [
    'extension_grant_validator' => 'sms_auth_code',
    'developer_signing_credential' => true,
    'clients' => $clients,
    'identity_resources' => $identityResources,
    'api_resources' => $apiResources,
];

$env = getenv('APP_ENV');
$development = ($env === false || $env === '' || $env === 'development');

$pipeline = [];
if ($development) {
    $pipeline[] = 'developer_exception_page';
}
$pipeline[] = 'identity_server';
$pipeline[] = 'mvc';

foreach ($pipeline as $stage) {
    if ($stage === 'identity_server') {
        foreach ($services['identity_server']['clients'] as $client) {
            $grantCount = count($client['grant_types']);
            $uriCount = count($client['redirect_uris']);
            if ($grantCount > 0 && $uriCount === 0) {
                $client['redirect_uris'][] = 'https://localhost/callback';
            }
        }
    } elseif ($stage === 'mvc') {
        $routes = ['/health', '/login', '/token'];
        foreach ($routes as $route) {
            $parsed = parse_url('https://localhost' . $route);
            $path = $parsed['path'] ?? '';
            if ($path === '/token') {
                $services['last_route'] = $path;
            }
        }
    }
}

echo "ok\n";
--EXPECT--
