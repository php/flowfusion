--TEST--
Translated seed (inferredbugs-java: bonecp/1)
--FILE--
<?php

class MockConnection {
    public function close() {}
    public function getAutoCommit() { return false; }
    public function getTransactionIsolation() { return 0; }
    public function setTransactionIsolation($level) {}
}

class MockPool {
    public function shutdown() {}
    public function getConnection() { return new MockConnection(); }
}

class BoneCPConnectionProvider {
    private $pool;
    private $autocommit = true;
    private $isolation = 8;

    public function close() {
        $this->pool->shutdown();
    }

    public function closeConnection($connection) {
        $connection->close();
    }

    public function configure($properties) {
        if ($properties->get('driver') === null) {
            throw new Exception("Driver not set");
        }
        if ($properties->get('url') === 'somethinginvalid') {
            throw new Exception("Invalid URL");
        }
        $this->pool = new MockPool();
    }

    public function getConnection() {
        $connection = $this->pool->getConnection();
        if ($connection->getTransactionIsolation() !== $this->isolation) {
            $connection->setTransactionIsolation($this->isolation);
        }
        return $connection;
    }
}

class Properties {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function get($key) {
        return $this->data[$key] ?? null;
    }
}

$mockProperties = new Properties([
    'driver' => 'com.jolbox.bonecp.MockJDBCDriver',
    'url' => 'jdbc:mock',
]);

$testClass = new BoneCPConnectionProvider();
$testClass->configure($mockProperties);

$connection = $testClass->getConnection();
$testClass->closeConnection($connection);
$testClass->close();
--EXPECT--
