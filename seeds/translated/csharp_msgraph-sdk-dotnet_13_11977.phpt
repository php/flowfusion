--TEST--
Translated seed (inferredbugs-csharp: msgraph-sdk-dotnet/13)
--FILE--
<?php
$cloudList = [
    'Global' => 'https://graph.microsoft.com',
    'US_GOV' => 'https://graph.microsoft.us',
    'China' => 'https://microsoftgraph.chinacloudapi.cn',
    'Germany' => 'https://graph.microsoft.de',
];

$handlers = [
    ['inner' => null],
    ['inner' => null],
    ['inner' => null],
];

$pipeline = null;
for ($i = count($handlers) - 1; $i >= 0; $i--) {
    if ($handlers[$i] === null) {
        throw new Exception('null handler');
    }
    if ($handlers[$i]['inner'] !== null) {
        throw new Exception('unexpected inner handler');
    }
    $handlers[$i]['inner'] = $pipeline;
    $pipeline = $handlers[$i];
}

$nationalCloud = 'Global';
$version = 'v1.0';
if (!array_key_exists($nationalCloud, $cloudList)) {
    throw new Exception('unexpected national cloud');
}
$baseAddress = $cloudList[$nationalCloud] . '/' . $version;

$headers = [];
$headers['Sdk-Version'] = 'Graph-1.0.0';
$headers['Cache-Control'] = 'no-cache, no-store';

echo $baseAddress . "\n";
echo $headers['Sdk-Version'] . "\n";
echo $headers['Cache-Control'] . "\n";
?>
--EXPECT--
