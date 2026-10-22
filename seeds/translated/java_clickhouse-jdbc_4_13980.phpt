--TEST--
Translated seed (inferredbugs-java: clickhouse-jdbc/4)
--FILE--
<?php
class CHDriver {
    public function __construct() {
        echo "Driver registered\n";
    }

    public function connect($url, $info) {
        echo "Creating connection\n";
        return new CHConnection($url, $info);
    }

    public function acceptsURL($url) {
        return strpos($url, "jdbc:clickhouse:") === 0;
    }

    public function getPropertyInfo($url, $info) {
        return [];
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

    public function getParentLogger() {
        throw new Exception("SQLFeatureNotSupportedException");
    }
}

class CHConnection {
    public function __construct($url, $info) {
        // Simulated connection creation
    }
}

$driver = new CHDriver();
$url = "jdbc:clickhouse:localhost:8123";
$info = [];
if ($driver->acceptsURL($url)) {
    $connection = $driver->connect($url, $info);
}
?>
--EXPECT--
