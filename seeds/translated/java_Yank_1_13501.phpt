--TEST--
Translated seed (inferredbugs-java: Yank/1)
--FILE--
<?php

class DBConnectionManager {
    public static $INSTANCE;

    public function getConnection($poolName) {
        // Simulate getting a connection, return a mock connection object
        return new class {
            public function setAutoCommit($autoCommit) {}
            public function commit() {}
            public function rollback() {}
        };
    }

    public function freeConnection($con) {}
}

DBConnectionManager::$INSTANCE = new DBConnectionManager();

function executeSQL($poolName, $sql, $params) {
    $returnInt = 0;
    $con = null;

    try {
        $con = DBConnectionManager::$INSTANCE->getConnection($poolName);
        if ($con == null) {
            throw new Exception("Connection could not be obtained");
        }

        $con->setAutoCommit(false);
        // Simulate execution and return a random number of affected rows
        $returnInt = rand(0, 100);
        $con->commit();
    } catch (Exception $e) {
        $con->rollback();
    } finally {
        DBConnectionManager::$INSTANCE->freeConnection($con);
    }

    return $returnInt;
}

$poolName = "default";
$sql = "INSERT INTO test_table (name) VALUES (?)";
$params = ["test"];

executeSQL($poolName, $sql, $params);
--EXPECT--
