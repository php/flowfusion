--TEST--
Translated seed (inferredbugs-java: LWC/8)
--FILE--
<?php

$revision = 2;
$baseUrl = "http://metrics.griefcraft.com";
$reportUrl = "/report/%s";
$configFile = "config.json";
$pingInterval = 10;
$guid = null;

function getLWCGUID() {
    return uniqid();
}

if (!file_exists($configFile)) {
    $guid = getLWCGUID();
    file_put_contents($configFile, json_encode(["guid" => $guid, "opt-out" => false]));
} else {
    $config = json_decode(file_get_contents($configFile), true);
    $guid = $config['guid'];
}

if ($config['opt-out'] ?? false) {
    exit;
}

$postData = http_build_query([
    'guid' => $guid,
    'version' => '1.0', // Placeholder version
    'server' => 'PHP/8.0', // Placeholder server version
    'players' => 0, // Placeholder player count
    'revision' => $revision
]);

$url = $baseUrl . sprintf($reportUrl, 'PluginName');

$context = stream_context_create([
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => $postData
    ]
]);

$response = file_get_contents($url, false, $context);

if (strpos($response, "OK") === 0) {
    return;
} elseif (strpos($response, "ERR") === 0) {
    throw new Exception($response);
}

sleep($pingInterval * 60);
--EXPECT--
