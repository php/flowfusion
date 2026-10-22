--TEST--
Translated seed (inferredbugs-java: blynk-server/32)
--FILE--
<?php

$email = "dmitriy@blynk.cc";
$pass = "b";
$appName = "Blynk";

$tokens = [];
$count = 300;

$user = [
    'email' => $email,
    'passwordHash' => hash('sha256', $pass . $email),
    'appName' => $appName,
    'purchaseEnergy' => 98000,
    'dashBoards' => []
];

for ($i = 1; $i <= $count; $i++) {
    $user['dashBoards'][] = [
        'id' => $i,
        'theme' => 'Blynk',
        'isActive' => true
    ];
}

for ($i = 1; $i <= $count; $i++) {
    $tokens[] = 'token_' . $i; // Simulates token generation
}

file_put_contents("/path/300_tokens.txt", implode(PHP_EOL, $tokens));
file_put_contents("/path/{$email}.{$appName}.user", json_encode($user));
--EXPECT--
