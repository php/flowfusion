--TEST--
Translated seed (inferredbugs-csharp: core-data/3)
--FILE--
<?php
class PropertyAssist {
    public string $DbStoreKey = '';
    public $Transaction = null;
    public $ConnectionFunc = null;
    public $TableNameFunc = null;
    public $CreateScriptFunc = null;
    public string $ExecuteScript = '';
    public function __construct(string $dbStoreKey = '') { $this->DbStoreKey = $dbStoreKey; }
    public function __get($name) { return false; }
    public function __set($name, $value) { $this->$name = $value; }
    public function InTransaction(): bool { return false; }
    public function OpenConnection(bool $isMaster = false, bool $ignoreTransaction = false) {
        $connection = new PDO('sqlite::memory:');
        if ($connection->query('SELECT 1') === false) throw new Exception('connection failed');
        return $connection;
    }
}

$p = new PropertyAssist('');
$p->DbStoreKey = str_repeat('x', 0);
$p->ConnectionFunc = fn(bool $m) => '';
$p->TableNameFunc = fn() => '';
$p->CreateScriptFunc = fn(string $s) => $s;
$p->ExecuteScript = '';
$p->Transaction = null;
$p->InTransaction();
$p->OpenConnection(true, false);
echo $p->DbStoreKey;
--EXPECT--
