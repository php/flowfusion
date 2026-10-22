--TEST--
Translated seed (inferredbugs-java: ShiroJwt/2)
--FILE--
<?php

class CustomCache {
    private $cache = [];
    const PREFIX_SHIRO_CACHE = 'shiro:cache:';

    private function getKey($key) {
        return self::PREFIX_SHIRO_CACHE . $key;
    }

    public function get($key) {
        $k = $this->getKey($key);
        return isset($this->cache[$k]) ? $this->cache[$k] : null;
    }

    public function put($key, $value) {
        $this->cache[$this->getKey($key)] = $value;
        return $value;
    }

    public function remove($key) {
        $k = $this->getKey($key);
        if (isset($this->cache[$k])) {
            unset($this->cache[$k]);
        }
        return null;
    }

    public function clear() {
        $this->cache = [];
    }

    public function size() {
        return count($this->cache);
    }

    public function keys() {
        return array_keys($this->cache);
    }

    public function values() {
        return array_values($this->cache);
    }
}

$cache = new CustomCache();
$cache->put('account1', 'value1');
$cache->put('account2', 'value2');
$cache->remove('account1');
$size = $cache->size();
$keys = $cache->keys();
$values = $cache->values();
--EXPECT--
