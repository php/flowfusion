--TEST--
Translated seed (inferredbugs-csharp: iotedge/2)
--FILE--
<?php
$cache = [];
$connectionString = "HostName=example.azure-devices.net;DeviceId=device1;SharedAccessKey=abc123";
$certPath = getenv("EdgeModuleCACertificateFile");

if ($certPath !== false && $certPath !== '') {
    $cert = @file_get_contents($certPath);
    if ($cert !== false) {
        $cache['root_cert'] = $cert;
    }
}

if (!isset($cache[$connectionString])) {
    $cache[$connectionString] = [
        'connectionString' => $connectionString,
        'settings' => ['mqtt_tcp_only' => true],
        'cert' => $cache['root_cert'] ?? null,
    ];
}

echo $cache[$connectionString]['connectionString'];
?>
--EXPECT--
