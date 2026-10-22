--TEST--
Translated seed (inferredbugs-java: NettyGameServer/7)
--FILE--
<?php

$lock = new stdClass();
$sdWorldServers = [];
$sdGameServers = [];
$sdDbServers = [];

function loadServers($element) {
    $servers = [];
    foreach ($element->children() as $childElement) {
        $server = new stdClass();
        $server->data = (string)$childElement; // Simulating loading server data
        $servers[] = $server;
    }
    return $servers;
}

function initServers() {
    global $sdWorldServers, $sdGameServers, $sdDbServers;

    $xml = simplexml_load_file('config.xml'); // Simulation of FileUtil.getConfigURL
    $sdWorldServers = loadServers($xml->world);
    $sdGameServers = loadServers($xml->game);
    $sdDbServers = loadServers($xml->db);
}

function synchronizedInit($callback) {
    global $lock;
    $lockId = spl_object_hash($lock);
    if (flock($lockId, LOCK_EX)) {
        $callback();
        flock($lockId, LOCK_UN);
    }
}

synchronizedInit(function() {
    initServers();
});

$sdRpcServiceProvider = new stdClass(); // Simulating SdRpcServiceProvider
$xml = simplexml_load_file('service_config.xml'); // Simulation of FileUtil.getConfigURL
foreach ($xml->service as $childElement) {
    $sdRpcServiceProvider->data[] = (string)$childElement; // Simulating loading service data
}

synchronizedInit(function() use ($sdRpcServiceProvider) {
    // Assign to global if needed
});
--EXPECT--
