--TEST--
Translated seed (inferredbugs-java: HikariCP/22)
--FILE--
<?php

class StubConnection {
    // Simulate the behavior of the original StubConnection
}

class HikariConfig {
    public $minimumIdle;
    public $maximumPoolSize;
    public $initializationFailFast;
    public $connectionTestQuery;
    public $dataSourceClassName;

    public function __construct() {
        $this->minimumIdle = 0;
        $this->maximumPoolSize = 0;
        $this->initializationFailFast = false;
    }
}

class HikariDataSource {
    private $config;
    private $connections = [];

    public function __construct(HikariConfig $config) {
        $this->config = $config;
        // Initialize connection pool
        for ($i = 0; $i < $config->minimumIdle; $i++) {
            $this->connections[] = new StubConnection();
        }
    }

    public function getConnection() {
        if (count($this->connections) > 0) {
            return array_pop($this->connections);
        }
        return null; // Simulate no available connections
    }

    public function shutdown() {
        // Simulate shutdown behavior
        $this->connections = [];
    }
}

$config = new HikariConfig();
$config->minimumIdle = 1;
$config->maximumPoolSize = 1;
$config->initializationFailFast = true;
$config->connectionTestQuery = "VALUES 1";
$config->dataSourceClassName = "StubDataSource";

$ds = new HikariDataSource($config);

$connection = $ds->getConnection();
if ($connection !== null) {
    $unwrapped = $connection instanceof StubConnection ? $connection : null;
}

$ds->shutdown();
--EXPECT--
