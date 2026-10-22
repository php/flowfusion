--TEST--
Translated seed (inferredbugs-java: HikariCP/10)
--FILE--
<?php

class HikariConfig {
    private $minimumIdle;
    private $maximumPoolSize;
    private $idleTimeout;

    public function setMinimumIdle($min) {
        $this->minimumIdle = $min;
    }

    public function setMaximumPoolSize($max) {
        $this->maximumPoolSize = $max;
    }

    public function setIdleTimeout($timeout) {
        $this->idleTimeout = $timeout;
    }

    public function getMaximumPoolSize() {
        return $this->maximumPoolSize;
    }
}

class HikariDataSource {
    private $config;
    private $connections = [];
    private $idleTimeout;

    public function __construct($config) {
        $this->config = $config;
        $this->idleTimeout = $config->idleTimeout;
    }

    public function getConnection() {
        if (count($this->connections) < $this->config->getMaximumPoolSize()) {
            $connection = new stdClass(); // Placeholder for a connection object
            $this->connections[] = $connection;
            return $connection;
        }
        throw new Exception("Maximum pool size reached");
    }

    public function getTotalConnections() {
        return count($this->connections);
    }

    public function shutdown() {
        $this->connections = [];
    }
}

$config = new HikariConfig();
$config->setMinimumIdle(5);
$config->setMaximumPoolSize(60);
$config->setIdleTimeout(1000);

$ds = new HikariDataSource($config);

$connections = [];
for ($i = 0; $i < $config->getMaximumPoolSize(); $i++) {
    $connections[] = $ds->getConnection();
}

usleep(2500000); // Simulate waiting for connections to become idle

$ds->shutdown();
--EXPECT--
