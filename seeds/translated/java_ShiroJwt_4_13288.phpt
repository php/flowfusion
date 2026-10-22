--TEST--
Translated seed (inferredbugs-java: ShiroJwt/4)
--FILE--
<?php

$cache = [];

function getKey($key) {
    return 'shiro:cache:account:' . $key;
}

function exists($key) {
    global $cache;
    return isset($cache[$key]);
}

function getObject($key) {
    global $cache;
    return $cache[$key] ?? null;
}

function setObject($key, $value, $expire) {
    global $cache;
    $cache[$key] = $value;
    // Simulate expiration logic if needed
}

function delKey($key) {
    global $cache;
    unset($cache[$key]);
}

function flushDB() {
    global $cache;
    $cache = [];
}

function dbSize() {
    global $cache;
    return count($cache);
}

function keys() {
    global $cache;
    return array_keys($cache);
}

function cacheGet($key) {
    if (!exists(getKey($key))) {
        return null;
    }
    return getObject(getKey($key));
}

function cachePut($key, $value) {
    $shiroCacheExpireTime = 3600; // Simulated expiration time
    return setObject(getKey($key), $value, $shiroCacheExpireTime);
}

function cacheRemove($key) {
    if (!exists(getKey($key))) {
        return null;
    }
    delKey(getKey($key));
    return null;
}

function cacheClear() {
    flushDB();
}

function cacheSize() {
    return dbSize();
}

function cacheKeys() {
    return keys();
}

function cacheValues() {
    $keys = cacheKeys();
    $values = [];
    foreach ($keys as $key) {
        $values[] = getObject(getKey($key));
    }
    return $values;
}

// Usage demonstration
cachePut('user1', 'data1');
cachePut('user2', 'data2');
cacheGet('user1');
cacheClear();
--EXPECT--
