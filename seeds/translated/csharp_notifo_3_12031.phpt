--TEST--
Translated seed (inferredbugs-csharp: notifo/3)
--FILE--
<?php
$config = [
    'email' => [
        'amazonSES' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => '',
            'password' => '',
            'use_tls' => false,
        ],
    ],
];

$section = $config['email']['amazonSES'] ?? [];
$options = (object)$section;

$server = [
    'host' => $options->host ?? 'localhost',
    'port' => $options->port ?? 25,
    'username' => $options->username ?? '',
    'password' => $options->password ?? '',
    'use_tls' => $options->use_tls ?? false,
];

$sender = [
    'server' => $server,
    'from' => 'test@example.com',
    'to' => 'test@example.com',
];

echo $sender['server']['host'] . ':' . $sender['server']['port'] . PHP_EOL;
--EXPECT--
