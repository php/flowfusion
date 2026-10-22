--TEST--
Translated seed (inferredbugs-csharp: kafka-streams-dotnet/1)
--FILE--
<?php
$config = [
    'application.id' => 'test-app',
    'bootstrap.servers' => '192.168.56.1:9092',
    'sasl.mechanism' => 'SCRAM-SHA-512',
    'sasl.username' => 'admin',
    'sasl.password' => 'admin',
    'security.protocol' => 'SaslPlaintext',
    'num.stream.threads' => 2,
];

$store = [];
$input = ['a' => 'test', 'b' => 'value', 'c' => 'not-test'];
$ktable = [];
foreach ($input as $k => $v) {
    $ktable[$k] = $v;
    $store[$k] = $v;
}

$filtered = [];
foreach ($ktable as $k => $v) {
    if (strpos($v, 'test') === false) {
        $filtered[$k] = $v;
    }
}

echo count($filtered) . PHP_EOL;
--EXPECT--
