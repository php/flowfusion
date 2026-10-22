--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/5)
--FILE--
<?php
$serverUrl1 = "http://myServer1.com:1234";
$serverUrl2 = "invalidUrl";
$serverUrl3 = "http://myServer2.com:1234";
$serverUrls = $serverUrl1 . "," . $serverUrl2 . "," . $serverUrl3;

$parts = array_map('trim', explode(',', $serverUrls));
$parsed = [];
$logs = [];

foreach ($parts as $u) {
    if (filter_var($u, FILTER_VALIDATE_URL)) {
        $parsed[] = $u;
    } else {
        $logs[] = "Error Config: Failed parsing server URL from env: urls, value: $u";
    }
}

if (!isset($parsed[0]) || strtolower(rtrim($parsed[0], '/')) !== strtolower($serverUrl1)) {
    throw new Exception('bad url 1');
}
if (!isset($parsed[1]) || strtolower(rtrim($parsed[1], '/')) !== strtolower($serverUrl3)) {
    throw new Exception('bad url 2');
}
if (!isset($logs[0]) || $logs[0] !== "Error Config: Failed parsing server URL from env: urls, value: $serverUrl2") {
    throw new Exception('bad log');
}

$logLevel = "InvalidLogLevel";
$levels = ['Debug', 'Error', 'Info', 'Warning'];
$level = in_array($logLevel, $levels, true) ? $logLevel : 'Error';
if ($level !== 'Error') {
    throw new Exception('bad level');
}

echo "OK\n";
?>
--EXPECT--
