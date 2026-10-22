--TEST--
Translated seed (inferredbugs-java: clickhouse-jdbc/3)
--FILE--
<?php

class CHDriver {
    public function connect($url) {
        echo "Creating connection\n";
        return new CHConnection($url);
    }

    public function acceptsURL($url) {
        return strpos($url, "jdbc:clickhouse:") === 0;
    }

    public function getMajorVersion() {
        return 0;
    }

    public function getMinorVersion() {
        return 0;
    }

    public function jdbcCompliant() {
        return false;
    }
}

class CHConnection {
    private $url;

    public function __construct($url) {
        $this->url = $url;
    }
}

$driver = new CHDriver();
$url = "jdbc:clickhouse:localhost:8123";

if ($driver->acceptsURL($url)) {
    $connection = $driver->connect($url);
}
--EXPECT--
