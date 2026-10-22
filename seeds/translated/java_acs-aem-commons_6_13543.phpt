--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/6)
--FILE--
<?php

$ttl = 300; // 5 minutes
$cache = [];
$serveAuthenticatedFromCache = false;

function isAnonymousRequest($request) {
    return !isset($request['authType']) || !isset($request['remoteUser']);
}

function getIncludeAsString($path, $request) {
    return "Content for $path"; // Simulated fetch
}

function currentMillis() {
    return round(microtime(true) * 1000);
}

$request = ['authType' => null, 'remoteUser' => null]; // Simulated request
$path = '/error/page';

$start = currentMillis();
if (!$serveAuthenticatedFromCache && !isAnonymousRequest($request)) {
    $data = getIncludeAsString($path, $request);
} else {
    if (!isset($cache[$path]) || time() - $cache[$path]['timestamp'] > $ttl) {
        // Cache Miss
        $data = getIncludeAsString($path, $request);
        $cache[$path] = ['data' => $data, 'timestamp' => time(), 'hits' => 0, 'misses' => 1];
        echo "Served cache MISS for [ $path ] in [ " . (currentMillis() - $start) . " ] ms\n";
    } else {
        // Cache Hit
        $data = $cache[$path]['data'];
        $cache[$path]['hits']++;
        echo "Served cache HIT for [ $path ] in [ " . (currentMillis() - $start) . " ] ms\n";
    }
}
--EXPECT--
