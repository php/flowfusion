--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/31)
--FILE--
<?php
$serverUrl = "http://myServer.com:1234";
$u = parse_url($serverUrl);
echo ($u['scheme'] ?? '') . "://" . ($u['host'] ?? '') . (isset($u['port']) ? ":" . $u['port'] : "") . "\n";
echo rtrim($serverUrl, "/") . "/\n";

$invalid = "InvalidUrl";
$u = parse_url($invalid);
echo (($u === false || !isset($u['scheme'], $u['host'])) ? "http://localhost" : $invalid) . "\n";

$serverUrls = "http://myServer1.com:1234,invalidUrl,http://myServer2.com:1234";
$parts = explode(",", $serverUrls);
$parsed = [];
$logs = [];
foreach ($parts as $p) {
    $u = parse_url($p);
    if ($u === false || !isset($u['scheme'], $u['host'])) {
        $logs[] = "{TestAgentConfigurationReader} Failed parsing server URL from Origin urls $p";
        continue;
    }
    $parsed[] = $p;
}
echo count($parsed) . "\n";
echo $parsed[0] . "\n";
echo rtrim($parsed[0], "/") . "/\n";
echo $parsed[1] . "\n";
echo rtrim($parsed[1], "/") . "/\n";
echo $logs[0] . "\n";

$secretToken = "secretToken";
echo $secretToken . "\n";

echo "Error\n";
echo "Debug\n";
echo "Error\n";
echo "Information\n";
echo "Warning\n";
echo "Error\n";

$serviceName = getenv("ELASTIC_APM_SERVICE_NAME");
if ($serviceName === false || $serviceName === "") $serviceName = "testhost";
echo str_replace('.', '_', $serviceName) . "\n";

$elasticToken = [174, 116, 0, 210, 193, 137, 207, 34];
$mscorlibToken = [183, 122, 92, 86, 25, 52, 224, 137];
$tokens = [
    $elasticToken,
    [0],
    [],
    [$elasticToken[0], $mscorlibToken[1], $elasticToken[2], $mscorlibToken[3], $elasticToken[4], $mscorlibToken[5], $elasticToken[6], $mscorlibToken[7]]
];
foreach ($tokens as $t) {
    $ok = count($t) === 8 && $t === $elasticToken;
    echo ($ok ? "1" : "0") . "\n";
}

putenv("ELASTIC_APM_SERVER_URLS=localhost");
$env = getenv("ELASTIC_APM_SERVER_URLS");
$u = parse_url($env);
$serverUrl = ($u === false || !isset($u['scheme'], $u['host'])) ? "http://localhost" : $env;
echo $serverUrl . "\n";
echo "1\n";
?>
--EXPECT--
