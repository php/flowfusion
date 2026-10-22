--TEST--
Translated seed (inferredbugs-java: Yank/6)
--FILE--
<?php

class DBConnectionManager {
    private static $instance;
    private $connections = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DBConnectionManager();
        }
        return self::$instance;
    }

    public function getConnection($poolName) {
        // Mocked connection for demonstration purposes
        return new PDO('sqlite::memory:');
    }

    public function freeConnection($con) {
        // No action needed for PDO
    }

    public function getSqlProperties() {
        return [
            'sqlKey' => 'SELECT * FROM table WHERE id = ?'
        ];
    }
}

function executeSQL($poolName, $sql, $params) {
    $dbConnectionManager = DBConnectionManager::getInstance();
    $con = $dbConnectionManager->getConnection($poolName);
    $returnInt = 0;

    try {
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
        $dbConnectionManager->freeConnection($con);
    }

    return $returnInt;
}

$poolName = 'default';
$sqlKey = 'sqlKey';
$sql = DBConnectionManager::getInstance()->getSqlProperties()[$sqlKey];
$params = [1];
executeSQL($poolName, $sql, $params);
--EXPECT--
