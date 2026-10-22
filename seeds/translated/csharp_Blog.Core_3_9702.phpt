--TEST--
Translated seed (inferredbugs-csharp: Blog.Core/3)
--FILE--
<?php

class MyContext
{
    private static $connectionString;
    private static $dbType;
    public static $connId;

    private $db;

    public static function init($connectionString, $dbType)
    {
        self::$connectionString = $connectionString;
        self::$dbType = $dbType;
    }

    public function __construct($sqlSugarClient)
    {
        if (empty(self::$connectionString)) {
            throw new InvalidArgumentException("数据库连接字符串为空");
        }

        $this->db = $sqlSugarClient;
    }

    public function getEntityDB($className)
    {
        return new SimpleClient($this->db, $className);
    }

    public function createTableByEntity($blnBackupTable, ...$entities)
    {
        $types = [];
        foreach ($entities as $entity) {
            $types[] = get_class($entity);
        }
        $this->createTableByEntityInternal($blnBackupTable, $types);
    }

    private function createTableByEntityInternal($blnBackupTable, $types)
    {
        if ($blnBackupTable) {
            $this->db->codeFirst()->backupTable()->initTables($types);
        } else {
            $this->db->codeFirst()->initTables($types);
        }
    }
}

class SimpleClient
{
    private $db;
    private $className;

    public function __construct($db, $className)
    {
        $this->db = $db;
        $this->className = $className;
    }
}

class DummySqlSugarClient
{
    public function codeFirst()
    {
        return $this;
    }

    public function backupTable()
    {
        return $this;
    }

    public function initTables($types)
    {
        // Simulate table creation
    }
}

function main()
{
    MyContext::init("dummy_connection_string", "SqlServer");
    $sqlSugarClient = new DummySqlSugarClient();
    $context = new MyContext($sqlSugarClient);

    $context->createTableByEntity(true, new stdClass(), new stdClass());
}

main();
--EXPECT--
