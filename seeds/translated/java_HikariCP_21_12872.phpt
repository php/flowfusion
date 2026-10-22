--TEST--
Translated seed (inferredbugs-java: HikariCP/21)
--FILE--
<?php

class HikariConfig {
    private $minimumIdle;
    private $maximumPoolSize;
    private $initializationFailFast;
    private $connectionTestQuery;
    private $dataSourceClassName;
    private $maxLifetime;

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

    public function setDataSourceClassName($value) {
        $this->dataSourceClassName = $value;
    }

    public function setMaxLifetime($value) {
        $this->maxLifetime = $value;
    }
}

class HikariDataSource {
    private $config;
    private $connections = [];
    
    public function __construct($config) {
        $this->config = $config;
        $this->connections[] = new StubConnection();
    }
    
    public function getConnection() {
        return array_pop($this->connections);
    }
    
    public function shutdown() {
        $this->connections = [];
    }
}

class StubConnection {
    public function close() {
        // Simulate closing the connection
    }
}

function testMaxLifetime() {
    $config = new HikariConfig();
    $config->setMinimumIdle(0);
    $config->setMaximumPoolSize(1);
    $config->setInitializationFailFast(true);
    $config->setConnectionTestQuery("VALUES 1");
    $config->setMaxLifetime(700); // 700 milliseconds for max lifetime

    $ds = new HikariDataSource($config);

    $connection = $ds->getConnection();
    usleep(800000); // sleep for 800 milliseconds

    $connection2 = $ds->getConnection(); // should be a new connection after max lifetime

    $connection->close();
    $connection2->close();
    
    $ds->shutdown();
}

testMaxLifetime();
--EXPECT--
