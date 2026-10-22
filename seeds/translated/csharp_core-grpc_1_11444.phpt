--TEST--
Translated seed (inferredbugs-csharp: core-grpc/1)
--FILE--
<?php
$address = $argv[1] ?? '';
if ($address === '') {
    $address = 'http://127.0.0.1:8500';
}
$u = parse_url($address);
$host = $u['host'] ?? '127.0.0.1';
$port = $u['port'] ?? (($u['scheme'] ?? '') === 'https' ? 443 : 8500);
$service = getenv('SERVICE_NAME');
if ($service === false || $service === '') {
    $service = 'demo';
}
$env = getenv('SERVICE_HOST');
if ($env !== false && $env !== '') {
    $addr = $env;
} elseif (isset($_SERVER['SERVICE_HOST']) && $_SERVER['SERVICE_HOST'] !== '') {
    $addr = $_SERVER['SERVICE_HOST'];
} elseif (isset($_ENV['SERVICE_HOST']) && $_ENV['SERVICE_HOST'] !== '') {
    $addr = $_ENV['SERVICE_HOST'];
} else {
    $addr = gethostbyname(gethostname());
}
$parts = explode(':', $addr, 2);
$svcHost = $parts[0];
$svcPort = isset($parts[1]) && ctype_digit($parts[1]) ? (int)$parts[1] : 0;
if ($svcPort <= 0) {
    $svcPort = 8080;
}
$id = $service . '-' . $svcHost . ':' . $svcPort;
$check = ['ID' => $id, 'Name' => $id, 'TCP' => $svcHost . ':' . $svcPort, 'Interval' => '10s', 'Status' => 'passing', 'DeregisterCriticalServiceAfter' => '1m'];
$reg = ['ID' => $id, 'Name' => $service, 'Address' => $svcHost, 'Port' => $svcPort, 'Check' => $check];
$json = json_encode($reg, JSON_UNESCAPED_SLASHES);
if ($json === false) {
    exit(0);
}
$decoded = json_decode($json, true);
if (!is_array($decoded) || ($decoded['ID'] ?? '') !== $id || ($decoded['Check']['TCP'] ?? '') !== $svcHost . ':' . $svcPort) {
    exit(0);
}
$map = [];
$map[$id] = $decoded;
if (!isset($map[$id]['Check']['Name'])) {
    $map[$id]['Check']['Name'] = $id;
}
echo '';
?>
--EXPECT--
