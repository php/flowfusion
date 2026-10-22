--TEST--
Translated seed (inferredbugs-java: HikariCP/1)
--FILE--
<?php

class ConnectionProxy {
    public function prepare($query) {
        return new PreparedStatementProxy($this, $query);
    }
}

class PreparedStatementProxy {
    protected $connection;
    protected $query;

    public function __construct($connection, $query) {
        $this->connection = $connection;
        $this->query = $query;
    }

    public function executeQuery() {
        return $this->__executeQuery();
    }

    protected function __executeQuery() {
        $resultSet = $this->mockExecuteQuery();
        if ($resultSet === null) {
            return null;
        }
        return $this->getProxyResultSet($resultSet);
    }

    protected function mockExecuteQuery() {
        // Simulating a database query result.
        return ['row1', 'row2', 'row3'];
    }

    protected function getProxyResultSet($resultSet) {
        // Simulating a proxy result set.
        return array_map(function($row) {
            return "Proxy: " . $row;
        }, $resultSet);
    }
}

// Main entry point.
$connection = new ConnectionProxy();
$statement = $connection->prepare("SELECT * FROM table");
$result = $statement->executeQuery();

foreach ($result as $row) {
    echo $row . PHP_EOL;
}
--EXPECT--
