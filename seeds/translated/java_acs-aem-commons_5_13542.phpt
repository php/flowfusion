--TEST--
Translated seed (inferredbugs-java: acs-aem-commons/5)
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

    public function setExpiresIn($ttl) {
        $this->expiresIn = time() + $ttl;
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

$ttl = 300; // 5 minutes
$cache = [];

function getIncludeAsString($path) {
    return "Data for $path"; // Simulating fetching data
}

$path = "example/path";
$anonymousRequest = true; // Simulating an anonymous request

if (!$anonymousRequest) {
    $data = getIncludeAsString($path);
} else {
    if (!isset($cache[$path]) || $cache[$path]->isExpired()) {
        $cacheEntry = new CacheEntry();
        $data = getIncludeAsString($path);
        $cacheEntry->setData($data);
        $cacheEntry->setExpiresIn($GLOBALS['ttl']);
        $cacheEntry->incrementMisses();
        $cache[$path] = $cacheEntry;
    } else {
        $cacheEntry = $cache[$path];
        $data = $cacheEntry->getData();
        $cacheEntry->incrementHits();
        $cache[$path] = $cacheEntry;
    }
}

echo $data;
--EXPECT--
