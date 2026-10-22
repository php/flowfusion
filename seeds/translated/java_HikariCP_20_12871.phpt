--TEST--
Translated seed (inferredbugs-java: HikariCP/20)
--FILE--
<?php

class HikariConfig {
    private $minimumIdle;
    private $maximumPoolSize;
    private $initializationFailFast;
    private $connectionTestQuery;

    public function setMinimumIdle($value) {
        $this->minimumIdle = $value;
    }

    public function setMaximumPoolSize($value) {
        $this->maximumPoolSize = $value;
    }

    public function setInitializationFailFast($value) {
        $this->initializationFailFast = $value;
    }

    public function setConnectionTestQuery($value) {
        $this->connectionTestQuery = $value;
    }
}

class HikariDataSource {
    private $config;
    private $connections = [];
    private $totalConnections = 0;

    public function __construct(HikariConfig $config) {
        $this->config = $config;
        for ($i = 0; $i < $config->maximumPoolSize; $i++) {
            $this->connections[] = new stdClass(); // Simulate connections
        }
    }

    public function getConnection() {
        if (count($this->connections) > 0) {
            $this->totalConnections++;
            return array_pop($this->connections);
        }
        return null;
    }

    public function shutdown() {
        $this->connections = [];
    }

    public function getTotalConnections() {
        return $this->totalConnections;
    }

    public function getIdleConnections() {
        return count($this->connections);
    }
}

function testMaxLifetime() {
    $config = new HikariConfig();
    $config->setMinimumIdle(0);
    $config->setMaximumPoolSize(1);
    $config->setInitializationFailFast(true);
    $config->setConnectionTestQuery("VALUES 1");

    $ds = new HikariDataSource($config);

    $connection = $ds->getConnection();
    $firstConnection = $connection;

    $connection = $ds->getConnection();
    $secondConnection = $connection;

    if ($firstConnection !== $secondConnection) {
        echo "Expected the same connection\n";
    }

    $ds->shutdown();
}

testMaxLifetime();
--EXPECT--
