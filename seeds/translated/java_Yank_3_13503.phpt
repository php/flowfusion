--TEST--
Translated seed (inferredbugs-java: Yank/3)
--FILE--
<?php

class DBConnectionManager {
    private static $instance;
    private $pdo;

    private function __construct() {
        $this->pdo = new PDO('sqlite::memory:'); // Example using SQLite in memory
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("CREATE TABLE test (id INTEGER PRIMARY KEY, value TEXT)");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

function executeSQL($sql, $params) {
    $connection = DBConnectionManager::getInstance()->getConnection();
    $connection->beginTransaction();
    try {
        $stmt = $connection->prepare($sql);
        $stmt->execute($params);
        $connection->commit();
    } catch (Exception $e) {
        $connection->rollBack();
    }
}

$sqlInsert = "INSERT INTO test (value) VALUES (?)";
executeSQL($sqlInsert, ['test value']);

$sqlSelect = "SELECT * FROM test";
$connection = DBConnectionManager::getInstance()->getConnection();
foreach ($connection->query($sqlSelect) as $row) {
    echo $row['id'] . ": " . $row['value'] . PHP_EOL;
}
--EXPECT--
