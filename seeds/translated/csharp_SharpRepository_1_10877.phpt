--TEST--
Translated seed (inferredbugs-csharp: SharpRepository/1)
--FILE--
<?php
$cfg = ['connectionString' => 'sqlite::memory:', 'dbContextType' => 'PDO'];
$pdo = null;

if ($cfg['connectionString'] === '' && $pdo === null) {
    exit(1);
}

if (!empty($cfg['dbContextType'])) {
    $type = $cfg['dbContextType'];
} else {
    $type = null;
}

$connectionString = $cfg['connectionString'];

if ($type === null) {
    $pdo = new PDO($connectionString);
} else {
    $pdo = new $type($connectionString);
}

if ($pdo === null) {
    $pdo = new PDO($connectionString);
}

$stmt = $pdo->query('SELECT 1');
$stmt->fetchAll();

$cfg2 = ['connectionString' => 'sqlite::memory:', 'dbContextType' => 'PDO'];
if ($cfg2['connectionString'] === '') {
    exit(1);
}

$type2 = !empty($cfg2['dbContextType']) ? $cfg2['dbContextType'] : null;
$pdo2 = $type2 === null ? new PDO($cfg2['connectionString']) : new $type2($cfg2['connectionString']);
$pdo2->query('SELECT 2')->fetchAll();
?>
--EXPECT--
