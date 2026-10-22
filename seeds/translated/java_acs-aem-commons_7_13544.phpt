--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/7)
--FILE--
<?php

class CacheEntry {
    private $data;
    private $expiresIn;
    private $hits = 0;
    private $misses = 0;

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function setExpiresIn($seconds) {
        $this->expiresIn = time() + $seconds;
    }

    public function isExpired() {
        return time() > $this->expiresIn;
    }

    public function incrementHits() {
        $this->hits++;
    }

    public function incrementMisses() {
        $this->misses++;
    }

    public function getHits() {
        return $this->hits;
    }

    public function getMisses() {
        return $this->misses;
    }
}

$cache = [];
$ttl = 300; // Default TTL for cache
$serveAuthenticatedFromCache = false; // Default behavior

function isAnonymousRequest() {
    return true; // Simulate anonymous request
}

function getIncludeAsString($path) {
    return "Data for $path"; // Mock data retrieval
}

$path = "example/path"; // Example path

if (!$serveAuthenticatedFromCache && !isAnonymousRequest()) {
    getIncludeAsString($path);
} else {
    $start = microtime(true);

    if (!isset($cache[$path]) || $cache[$path]->isExpired()) {
        $cacheEntry = new CacheEntry();

        $data = getIncludeAsString($path);
        $cacheEntry->setData($data);
        $cacheEntry->setExpiresIn($ttl);
        $cacheEntry->incrementMisses();

        $cache[$path] = $cacheEntry;

        echo "Served cache MISS for [ $path ] in [ " . (microtime(true) - $start) . " ] seconds\n";
    } else {
        $cacheEntry = $cache[$path];
        $data = $cacheEntry->getData();
        $cacheEntry->incrementHits();
        $cache[$path] = $cacheEntry;

        echo "Served cache HIT for [ $path ] in [ " . (microtime(true) - $start) . " ] seconds\n";
    }
}
--EXPECT--
