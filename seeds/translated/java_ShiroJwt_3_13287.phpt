--TEST--
Translated seed (inferredbugs-java: ShiroJwt/3)
--FILE--
<?php

class CustomCache {
    private $prefix = 'shiro:cache:';

    private function getKey($key) {
        return $this->prefix . $key;
    }

    public function get($key) {
        if (!file_exists($this->getKey($key))) {
            return null;
        }
        return unserialize(file_get_contents($this->getKey($key)));
    }

    public function put($key, $value, $expire) {
        file_put_contents($this->getKey($key), serialize($value));
        touch($this->getKey($key), time() + $expire);
    }

    public function remove($key) {
        $cacheKey = $this->getKey($key);
        if (!file_exists($cacheKey)) {
            return null;
        }
        unlink($cacheKey);
        return null;
    }

    public function clear() {
        array_map('unlink', glob($this->prefix . "*"));
    }

    public function size() {
        return count(glob($this->prefix . "*"));
    }

    public function keys() {
        return array_map('basename', glob($this->prefix . "*"));
    }

    public function values() {
        $keys = $this->keys();
        $values = [];
        foreach ($keys as $key) {
            $values[] = $this->get($key);
        }
        return $values;
    }
}

$cache = new CustomCache();
$cache->put('account1', 'data1', 10);
$cache->put('account2', 'data2', 10);
$cache->get('account1');
$cache->remove('account2');
$cache->size();
$cache->clear();
--EXPECT--
