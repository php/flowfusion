--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/29)
--FILE--
<?php
$serverUrl = "http://myServer.com:1234";
$u = parse_url($serverUrl);
echo ($u !== false && isset($u["scheme"], $u["host"], $u["port"]) ? $serverUrl : "http://localhost:8200") . PHP_EOL;
$serverUrl = "InvalidUrl";
$u = parse_url($serverUrl);
echo ($u !== false && isset($u["scheme"], $u["host"]) ? $serverUrl : "http://localhost:8200") . PHP_EOL;
$serverUrls = "http://myServer1.com:1234,invalidUrl,http://myServer2.com:1234";
$parsed = [];
foreach (explode(",", $serverUrls) as $s) {
    $u = parse_url($s);
    if ($u !== false && isset($u["scheme"], $u["host"])) $parsed[] = rtrim($s, "/") . "/";
}
echo implode("|", $parsed) . PHP_EOL;
putenv("ELASTIC_APM_SERVICE_NAME=My.Service.Test");
echo str_replace('.', '_', getenv("ELASTIC_APM_SERVICE_NAME") ?: "") . PHP_EOL;
$elasticToken = [174,116,0,210,193,137,207,34];
$mscorlibToken = [183,122,92,86,25,52,224,137];
$a = $elasticToken;
$b = [0];
$c = [];
$d = [$elasticToken[0], $mscorlibToken[1], $elasticToken[2], $mscorlibToken[3], $elasticToken[4], $mscorlibToken[5], $elasticToken[6], $mscorlibToken[7]];
echo ((count($a) === 8 && $a === $elasticToken) ? "1" : "0") . ((count($b) === 1 && $b === $elasticToken) ? "1" : "0") . ((count($c) === 0) ? "1" : "0") . ((count($d) === 8 && $d === $elasticToken) ? "1" : "0") . PHP_EOL;
?>
--EXPECT--
