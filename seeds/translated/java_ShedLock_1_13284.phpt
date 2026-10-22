--TEST--
Translated seed (inferredbugs-java: ShedLock/1)
--FILE--
<?php

define('TEST_SCHEMA_NAME', 'shedlock_test');

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=' . TEST_SCHEMA_NAME, 'SA', 'pass');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "CREATE TABLE IF NOT EXISTS test_table (id SERIAL PRIMARY KEY, value VARCHAR(100))";
$pdo->exec($sql);

$value = str_repeat('a', 100);
$sql = "INSERT INTO test_table (value) VALUES (:value)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':value', $value);
$stmt->execute();

$stmt = $pdo->query("SELECT * FROM test_table");
foreach ($stmt as $row) {
    echo $row['value'] . "\n";
}

$pdo->exec("DROP TABLE test_table");
--EXPECT--
