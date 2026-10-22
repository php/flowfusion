--TEST--
Translated seed (inferredbugs-csharp: msgraph-sdk-dotnet/12)
--FILE--
<?php
$cloudList = [
    'Global' => 'https://graph.microsoft.com',
    'US_GOV' => 'https://graph.microsoft.com',
    'China' => 'https://microsoftgraph.chinacloudapi.cn',
    'Germany' => 'https://graph.microsoft.de',
];

$version = 'v1.0';
foreach (['Global', 'China', 'Germany', 'US_GOV'] as $cloud) {
    if (!array_key_exists($cloud, $cloudList)) {
        throw new Exception("$cloud is an unexpected national cloud.");
    }
    $base = $cloudList[$cloud] . '/' . $version;
    parse_url($base);
}

$handlers = [
    null,
    new stdClass(),
];
$pipeline = null;
for ($i = count($handlers) - 1; $i >= 0; $i--) {
    $handler = $handlers[$i];
    if ($handler === null) {
        continue;
    }
    if (isset($handler->innerHandler)) {
        throw new Exception('unexpected inner handler');
    }
    $handler->innerHandler = $pipeline;
    $pipeline = $handler;
}

$nested = [1, 2, 3, 4, 5];
for ($i = count($nested) - 1; $i >= 0; $i--) {
    $x = $nested[$i];
    $y = $x + 0;
}

echo "";
?>
--EXPECT--
