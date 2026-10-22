--TEST--
Translated seed (inferredbugs-csharp: notifo/2)
--FILE--
<?php
$config = [
    'email' => [
        'smtp' => [
            'host' => '127.0.0.1',
            'port' => 25,
            'username' => '',
            'password' => '',
            'encryption' => null,
            'timeout' => 1,
        ],
    ],
];

$options = $config['email']['smtp'];
$server = new stdClass();
$server->options = $options;
$sender = new stdClass();
$sender->server = $server;
$sender->from = 'test@example.com';
$sender->to = 'test@example.com';

echo $sender->from . "\n";
echo $sender->to . "\n";
echo $sender->server->options['host'] . ":" . $sender->server->options['port'] . "\n";
--EXPECT--
