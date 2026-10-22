--TEST--
Translated seed (inferredbugs-csharp: titanium-web-proxy/1)
--FILE--
<?php
$u = parse_url($argv[1] ?? 'http://localhost/');
$host = $u['host'] ?? 'localhost';
$scheme = $u['scheme'] ?? 'http';

$autoDetectFailed = false;
$state = 'Uninitialized';
$automaticallyDetectSettings = true;
$automaticConfigurationScript = 'http://wpad/wpad.dat';
$bypassOnLocal = true;
$proxyInfo = ['http' => ['host' => 'proxy.example', 'port' => 8080]];

$session = true;
if (!$session || $state === 'UnrecognizedScheme') exit(0);

$proxyListString = null;
$errorCode = 1;
if ($automaticallyDetectSettings && !$autoDetectFailed) {
    $errorCode = 0;
    $autoDetectFailed = !in_array($errorCode, [0, 2, 3, 4], true);
    if ($errorCode === 5) {
        $state = 'UnrecognizedScheme';
        exit(0);
    }
}

if ($automaticConfigurationScript !== null && in_array($errorCode, [1, 5, 6, 7, 8, 9, 10], true))
    $errorCode = 0;

$state = $errorCode === 0 ? 'Completed' : 'CompilationFailure';
if ($state !== 'Completed') exit(0);

if ($proxyListString !== null && $proxyListString !== '') {
    $proxyListString = preg_replace('/\s+/', '', $proxyListString);
    $proxyList = explode(';', $proxyListString);
} else {
    $proxyList = null;
}

if ($proxyList !== null) {
    $proxy = $proxyList[0];
    $port = 80;
    $pos = strpos($proxy, ':');
    if ($pos !== false) {
        $port = (int)substr($proxy, $pos + 1);
        $proxy = substr($proxy, 0, $pos);
    }
    $systemProxy = ['host' => $proxy, 'port' => $port];
} elseif ($host === 'localhost' || $host === '127.0.0.1' || $host === '::1') {
    $systemProxy = null;
} elseif (isset($proxyInfo[$scheme])) {
    $systemProxy = $proxyInfo[$scheme];
} else {
    $systemProxy = null;
}

echo "";
?>
--EXPECT--
