--TEST--
Translated seed (inferredbugs-java: Yank/5)
--FILE--
<?php

class DBConnectionManager {
    private static $instance;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DBConnectionManager();
        }
        return self::$instance;
    }

    public function getConnection($poolName) {
        // Simulate getting a connection, return a dummy connection object
        return new \PDO('sqlite::memory:');
    }

    public function freeConnection($con) {
        // Simulate freeing a connection
    }

    public function getSqlProperties() {
        return [
            'select_user' => 'SELECT * FROM users WHERE id = ?',
            'insert_user' => 'INSERT INTO users (name) VALUES (?)'
        ];
    }
}

function executeSQL($poolName, $sql, $params) {
    $returnInt = 0;
    $con = null;

    try {
        $con = DBConnectionManager::getInstance()->getConnection($poolName);
        $con->beginTransaction();
        $stmt = $con->prepare($sql);
        $stmt->execute($params);
        $returnInt = $stmt->rowCount();
        $con->commit();
    } catch (Exception $e) {
        if ($con) {
            $con->rollBack();
        }
    } finally {
        DBConnectionManager::getInstance()->freeConnection($con);
    }

    return $returnInt;
}

$poolName = 'default';
$sql = DBConnectionManager::getInstance()->getSqlProperties()['insert_user'];
$params = ['John Doe'];

executeSQL($poolName, $sql, $params);
--EXPECT--
