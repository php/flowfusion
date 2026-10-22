--TEST--
Translated seed (inferredbugs-java: YCSB/1)
--FILE--
<?php

class RemoteCacheManager {
    public function __construct($props) {}
}

class RemoteCacheManagerHolder {
    private static ?RemoteCacheManager $cacheManager = null;

    public static function getInstance(array $props): RemoteCacheManager {
        if (self::$cacheManager === null) {
            self::$cacheManager = new RemoteCacheManager($props);
        }
        return self::$cacheManager;
    }
}

$props = [];
$instance = RemoteCacheManagerHolder::getInstance($props);

?>
--EXPECT--
