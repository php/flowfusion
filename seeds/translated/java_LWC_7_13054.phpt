--TEST--
Translated seed (inferredbugs-java: LWC/7)
--FILE--
<?php

$revision = 2;
$baseUrl = "http://metrics.griefcraft.com";
$reportUrl = "/report/%s";
$configFile = "plugins/PluginMetrics/config.yml";
$pingInterval = 10;

function loadConfig($file) {
    if (!file_exists($file)) {
        file_put_contents($file, "opt-out: false\nguid: " . generateGuid() . "\n");
    }
    return yaml_parse(file_get_contents($file));
}

function generateGuid() {
    return uuid_create(UUID_TYPE_RANDOM);
}

function postPlugin($plugin, $isPing, $guid) {
    global $baseUrl, $revision;

    $data = http_build_query([
        'guid' => $guid,
        'version' => $plugin['version'],
        'server' => php_uname('s') . ' ' . php_uname('r'),
        'players' => count($plugin['players']),
        'revision' => $revision
    ]);

    if ($isPing) {
        $data .= '&ping=true';
    }

    $url = $baseUrl . sprintf($reportUrl, $plugin['name']);
    $opts = [
        "http" => [
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n",
            "content" => $data,
        ]
    ];

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if (strpos($response, "OK") === 0) {
        return;
    } elseif (strpos($response, "ERR") === 0) {
        throw new Exception($response);
    }
}

function beginMeasuringPlugin($plugin) {
    global $configFile;

    $config = loadConfig($configFile);
    $guid = $config['guid'];
    
    if ($config['opt-out']) {
        return;
    }

    postPlugin($plugin, false, $guid);

    for ($i = 0; $i < 5; $i++) { // simulate 5 intervals for example
        sleep($pingInterval * 60);
        try {
            postPlugin($plugin, true, $guid);
        } catch (Exception $e) {
            echo "[Metrics] " . $e->getMessage() . "\n";
        }
    }
}

$plugin = ['name' => 'examplePlugin', 'version' => '1.0', 'players' => range(1, 10)];
beginMeasuringPlugin($plugin);

?>
--EXPECT--
