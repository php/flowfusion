--TEST--
Translated seed (inferredbugs-java: bonecp/3)
--FILE--
<?php

class MockPartition {
    private $connections = [];

    public function isUnableToCreateMoreTransactions() {
        return false;
    }

    public function getFreeConnections() {
        return $this->connections;
    }

    public function addConnection($connection) {
        $this->connections[] = $connection;
    }

    public function getMaxConnections() {
        return 10;
    }

    public function getAvailableConnections() {
        return count($this->connections);
    }
}

class ConnectionHandle {
    private $broken = false;

    public function isPossiblyBroken() {
        return $this->broken;
    }

    public function markAsBroken() {
        $this->broken = true;
    }
}

class TestBoneCP {
    private $partition;
    private $connections = [];
    
    public function __construct() {
        $this->partition = new MockPartition();
    }

    public function getConnection() {
        if ($this->partition->isUnableToCreateMoreTransactions()) {
            throw new Exception("Unable to create more transactions");
        }

        if (count($this->connections) > 0) {
            return array_pop($this->connections);
        } else {
            throw new Exception("No available connections");
        }
    }

    public function releaseConnection($connection) {
        if ($connection->isPossiblyBroken()) {
            $this->partition->addConnection($connection);
        }
    }

    public function simulateError() {
        foreach ($this->connections as $connection) {
            $connection->markAsBroken();
        }
    }
}

$test = new TestBoneCP();
$connection = new ConnectionHandle();
$test->releaseConnection($connection);
$test->simulateError();
try {
    $test->getConnection();
} catch (Exception $e) {
    // Handle exception
}
--EXPECT--
