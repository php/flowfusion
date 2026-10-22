--TEST--
Translated seed (inferredbugs-csharp: InstaSharper/1)
--FILE--
<?php
$username = $argv[1] ?? 'user';
$user = ['username' => $username, 'session' => null];
$device = ['model' => 'SAMSUNG_NOTE3', 'os' => 'Android'];
$requestMessage = ['device' => $device, 'headers' => ['User-Agent' => 'InstaSharper/Android']];
$proxy = ['scheme' => 'http', 'host' => '127.0.0.1', 'port' => 8080];

$api1 = [
    'user' => ['username' => $user['username']],
    'logger' => 'test',
    'request' => $requestMessage,
];
$api2 = [
    'user' => $user,
    'logger' => 'test',
    'request' => $requestMessage,
];
$api3 = [
    'proxy' => $proxy,
    'user' => $user,
    'handler' => ['proxy' => $proxy],
];

$loginResult = ['Succeeded' => true, 'Info' => ['Message' => '']];
if (!$loginResult['Succeeded']) {
    echo "Can't login: " . $loginResult['Info']['Message'] . PHP_EOL;
}
?>
--EXPECT--
