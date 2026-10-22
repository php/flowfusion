--TEST--
Translated seed (inferredbugs-java: Spigot-API/3)
--FILE--
<?php

$dataFolder = '/path/to/data/folder';
$filePath = $dataFolder . '/config.yml';
$isEnabled = false;

function getDataFolder() {
    global $dataFolder;
    return $dataFolder;
}

function isEnabled() {
    global $isEnabled;
    return $isEnabled;
}

function saveDefaultConfig() {
    global $dataFolder, $filePath;
    $outDir = dirname($filePath);
    if (!is_dir($outDir)) {
        mkdir($outDir, 0777, true);
    }
    if (!file_exists($filePath)) {
        file_put_contents($filePath, "default: value\n");
    }
}

function loadConfig() {
    global $filePath;
    if (file_exists($filePath)) {
        return yaml_parse_file($filePath);
    }
    return [];
}

saveDefaultConfig();
$config = loadConfig();
var_dump($config);
--EXPECT--
