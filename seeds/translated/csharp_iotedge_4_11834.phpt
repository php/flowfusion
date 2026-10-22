--TEST--
Translated seed (inferredbugs-csharp: iotedge/4)
--FILE--
<?php
$logLevel = 'information';
$levels = [
    'verbose' => 0,
    'debug' => 1,
    'info' => 2,
    'warning' => 3,
    'error' => 4,
    'fatal' => 5,
];

$input = $argv[1] ?? "  DeBuG  ";
$trimmed = trim($input);
if ($trimmed !== '') {
    $key = strtolower($trimmed);
    $logLevel = $levels[$key] ?? 'information';
}

static $factory = null;
if ($factory === null) {
    $factory = [
        'minimumLevel' => $logLevel,
        'template' => "{Timestamp:yyyy-MM-dd HH:mm:ss} [{Level:u3}] - {Message}{NewLine}{Exception}",
    ];
}

echo $factory['minimumLevel'] . PHP_EOL;
--EXPECT--
