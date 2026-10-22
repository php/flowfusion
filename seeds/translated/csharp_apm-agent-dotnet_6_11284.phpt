--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/6)
--FILE--
<?php
$serverUrl1 = "http://myServer1.com:1234";
$serverUrl2 = "invalidUrl";
$serverUrl3 = "http://myServer2.com:1234";
$serverUrls = $serverUrl1 . "," . $serverUrl2 . "," . $serverUrl3;

$logger = [];
$parsed = [];
foreach (explode(",", $serverUrls) as $value) {
    $url = filter_var($value, FILTER_VALIDATE_URL) ? $value : "http://default/";
    if ($url === "http://default/") {
        $logger[] = "Error Config: Failed parsing server URL, value: $value";
        continue;
    }
    $parsed[] = $url;
}

$logLevelValue = "InvalidLogLevel";
$allowed = ["Debug", "Error", "Info", "Warning"];
$logLevel = in_array($logLevelValue, $allowed, true) ? $logLevelValue : "Error";
if ($logLevelValue !== $logLevel) {
    $logger[] = "Error Config: Failed parsing log level, value: $logLevelValue. Defaulting to log level 'Error'";
}

echo $parsed[0] . "\n";
echo strtolower($parsed[0]) . "/\n";
echo $parsed[1] . "\n";
echo strtolower($parsed[1]) . "/\n";
echo $logLevel . "\n";
echo $logger[0] . "\n";
echo $logger[1] . "\n";
?>
--EXPECT--
