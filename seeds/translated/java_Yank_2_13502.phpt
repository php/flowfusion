--TEST--
Translated seed (inferredbugs-java: Yank/2)
--FILE--
<?php

class DBConnectionManager {
    public static function getConnection($poolName) {
        // Simulate obtaining a database connection
        return new PDO('sqlite::memory:'); // Using SQLite for simplicity
    }
    public static function freeConnection($con) {
        // Simulate freeing the database connection
        $con = null;
    }
}

function executeSQL($poolName, $sql, $params) {
    $con = DBConnectionManager::getConnection($poolName);
    if ($con === null) {
        throw new Exception("Connection could not be obtained");
    }

    $con->beginTransaction();
    try {
        $stmt = $con->prepare($sql);
        $stmt->execute($params);
        $con->commit();
    } catch (Exception $e) {
        $con->rollBack();
    } finally {
        DBConnectionManager::freeConnection($con);
    }
}

$poolName = 'default_pool';
$sql = 'CREATE TABLE test (id INTEGER PRIMARY KEY, value TEXT); INSERT INTO test (value) VALUES (?);';
$params = ['test_value'];

executeSQL($poolName, $sql, $params);
--EXPECT--
