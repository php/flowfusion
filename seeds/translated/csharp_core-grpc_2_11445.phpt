--TEST--
Translated seed (inferredbugs-csharp: core-grpc/2)
--FILE--
<?php
$config = $argv[1] ?? 'config.json';
if (!is_file($config)) {
    file_put_contents($config, json_encode([
        'service' => [
            'name' => 'svc',
            'maxRetry' => 3,
            'discovery' => [
                'consul' => ['path' => 'consul.json'],
                'endPoints' => [['host' => '127.0.0.1', 'port' => 5000], ['host' => '127.0.0.2', 'port' => 5001]]
            ]
        ]
    ]));
    file_put_contents('consul.json', json_encode(['service' => ['address' => '127.0.0.1:8500']]));
}
$cache = [];
$key = 'ClientBase';
if (isset($cache[$key]) && isset($cache[$key]['endpointStrategy'])) {
    return;
}
$service = json_decode(file_get_contents($config), true)['service'] ?? null;
if (!$service) {
    throw new Exception('service config error');
}
$discovery = $service['discovery'] ?? [];
$address = '';
$consulPath = $discovery['consul']['path'] ?? '';
if ($consulPath !== '') {
    $base = getcwd();
    $full = $base . DIRECTORY_SEPARATOR . $consulPath;
    if (!is_file($full)) {
        throw new Exception("[$consulPath] not exist at [$base]");
    }
    $consul = json_decode(file_get_contents($full), true);
    $address = $consul['service']['address'] ?? '';
}
if ($address !== '') {
    $strategy = ['type' => 'sticky', 'service' => $service['name'], 'address' => $address];
} else {
    $endPoints = [];
    foreach (($discovery['endPoints'] ?? []) as $ep) {
        $endPoints[] = [$ep['host'], $ep['port']];
    }
    $strategy = ['type' => 'ip', 'service' => $service['name'], 'endPoints' => $endPoints];
}
$cache[$key] = ['serviceName' => $service['name'], 'maxRetry' => $service['maxRetry'], 'endpointStrategy' => $strategy];
echo json_encode($cache[$key], JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
