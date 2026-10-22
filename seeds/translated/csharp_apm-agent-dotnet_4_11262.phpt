--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/4)
--FILE--
<?php
$serverUrl1 = "http://myServer1.com:1234";
$serverUrl2 = "invalidUrl";
$serverUrl3 = "http://myServer2.com:1234";
$serverUrls = $serverUrl1 . "," . $serverUrl2 . "," . $serverUrl3;

$urls = [];
$logs = [];
foreach (explode(",", $serverUrls) as $u) {
    $p = parse_url($u);
    if ($p === false || !isset($p["scheme"], $p["host"])) {
        $logs[] = "Error Config: Failed parsing server URL from EnvVar: urls, value: $u";
        continue;
    }
    $path = isset($p["path"]) ? $p["path"] : "";
    $urls[] = $p["scheme"] . "://" . $p["host"] . (isset($p["port"]) ? ":" . $p["port"] : "") . ($path === "" ? "/" : $path);
}

$logLevelValue = "InvalidLogLevel";
$allowed = ["Debug", "Error", "Info", "Warning"];
$logLevel = in_array($logLevelValue, $allowed, true) ? $logLevelValue : "Error";
if ($logLevelValue !== $logLevel) {
    $logs[] = "Error Config: Failed parsing log level from EnvVar: level, value: $logLevelValue. Defaulting to log level 'Error'";
}

echo $urls[0] . PHP_EOL;
echo strtolower($urls[0]) . PHP_EOL;
echo $urls[1] . PHP_EOL;
echo strtolower($urls[1]) . PHP_EOL;
echo $logLevel . PHP_EOL;
echo $logs[0] . PHP_EOL;
echo $logs[1] . PHP_EOL;
?>
--EXPECT--
