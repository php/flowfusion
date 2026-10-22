--TEST--
Translated seed (inferredbugs-csharp: WindowsDevicePortalWrapper/1)
--FILE--
<?php
$address = '';
$userName = 'user';
$password = 'pass';

if (trim($address) === '') {
    $address = 'localhost:10080';
}

$scheme = (strpos($address, '127.0.0.1') !== false || strpos($address, 'localhost') !== false) ? 'http' : 'https';
$connection = $scheme . '://' . $address;
$credentials = [$userName, $password];

$certificateData = "-----BEGIN CERTIFICATE-----\nMIIB\n-----END CERTIFICATE-----";
$cert = @openssl_x509_read($certificateData);
if ($cert !== false) {
    $parsed = openssl_x509_parse($cert);
    $issuer = isset($parsed['issuer']) ? implode(', ', array_map(function ($k, $v) { return $k . '=' . $v; }, array_keys($parsed['issuer']), $parsed['issuer'])) : '';
    if (strpos($issuer, 'DevicePortal') === false) {
        $cert = false;
    }
}

$ipConfig = [
    'Adapters' => [
        ['IpAddresses' => [['Address' => '0.0.0.0'], ['Address' => '169.254.1.2'], ['Address' => '192.168.1.50']]],
        ['IpAddresses' => [['Address' => '10.0.0.8']]]
    ]
];

$newConnection = null;
foreach ($ipConfig['Adapters'] as $adapter) {
    foreach ($adapter['IpAddresses'] as $addressInfo) {
        $a = $addressInfo['Address'];
        if ($a !== '0.0.0.0' && strpos($a, '169.') !== 0) {
            $scheme = (strpos($a, '127.0.0.1') !== false || strpos($a, 'localhost') !== false || false === false) ? 'http' : 'https';
            $newConnection = $scheme . '://' . $a;
            break 2;
        }
    }
}

if ($newConnection !== null) {
    $connection = $newConnection;
}

echo $connection, PHP_EOL;
?>
--EXPECT--
