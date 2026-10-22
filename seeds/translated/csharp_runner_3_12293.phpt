--TEST--
Translated seed (inferredbugs-csharp: runner/3)
--FILE--
<?php
$ua = [];
$ua[] = trim(php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('m'));
$defaultUa = $ua;
$retry = getenv('GITHUB_ACTIONS_RUNNER_HTTP_RETRY');
$retry = is_numeric($retry) ? (int)$retry : 3;
$retry = max(3, min(10, $retry));
$timeout = getenv('GITHUB_ACTIONS_RUNNER_HTTP_TIMEOUT');
if (is_numeric($timeout)) {
    $timeout = max(100, min(1200, (int)$timeout));
} else {
    $timeout = 100;
}
$acceptLanguages = [setlocale(LC_CTYPE, 0), 'C', 'en_US'];
$acceptLanguages = array_values(array_filter($acceptLanguages, fn($v) => $v !== '' && $v !== false && $v !== null));
$acceptLanguages = array_values(array_filter($acceptLanguages, fn($v) => $v !== 'C'));
$endpoint = [
    'authorization' => [
        'scheme' => 'OAuth',
        'parameters' => ['access_token' => 'token']
    ]
];
$credentials = null;
if (!empty($endpoint['authorization']) && !empty($endpoint['authorization']['scheme']) && !empty($endpoint['authorization']['parameters'])) {
    $scheme = $endpoint['authorization']['scheme'];
    $params = $endpoint['authorization']['parameters'];
    if ($scheme === 'OAuth' && array_key_exists('access_token', $params)) {
        $credentials = ['type' => 'oauth', 'token' => $params['access_token']];
    }
}
echo '';
?>
--EXPECT--
