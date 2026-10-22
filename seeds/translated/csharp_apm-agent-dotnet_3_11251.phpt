--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/3)
--FILE--
<?php
$serverUrl1 = "http://myServer1.com:1234";
$serverUrl2 = "invalidUrl";
$serverUrl3 = "http://myServer2.com:1234";
$serverUrls = [$serverUrl1, $serverUrl2, $serverUrl3];

$parsed = [];
$lines = [];
foreach ($serverUrls as $value) {
    $u = parse_url($value);
    if ($u === false || !isset($u["scheme"], $u["host"])) {
        $parsed[] = "http://localhost:8200/";
        $lines[] = "Error Config: Failed parsing server URL from env: server_urls, value: $value";
    } else {
        $path = isset($u["path"]) ? $u["path"] : "";
        $parsed[] = $u["scheme"] . "://" . $u["host"] . (isset($u["port"]) ? ":" . $u["port"] : "") . ($path === "" ? "/" : $path);
    }
}

$logLevelValue = "InvalidLogLevel";
$levels = ["Debug", "Error", "Info", "Warning"];
$logLevel = in_array($logLevelValue, $levels, true) ? $logLevelValue : "Error";
if ($logLevelValue !== $logLevel) {
    $lines[] = "Error Config: Failed parsing log level from env: log_level, value: $logLevelValue. Defaulting to log level 'Error'";
}

echo $parsed[0], "\n";
echo strtolower($parsed[0]), "\n";
echo $parsed[1], "\n";
echo strtolower($parsed[1]), "\n";
echo $parsed[2], "\n";
echo strtolower($parsed[2]), "\n";
echo $logLevel, "\n";
foreach ($lines as $line) {
    echo $line, "\n";
}
?>
--EXPECT--
