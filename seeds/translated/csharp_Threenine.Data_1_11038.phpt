--TEST--
Translated seed (inferredbugs-csharp: Threenine.Data/1)
--FILE--
<?php
declare(strict_types=1);

$db = new PDO('sqlite::memory:');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('CREATE TABLE test_categories (id INTEGER PRIMARY KEY, name TEXT NOT NULL)');
$db->exec('CREATE TABLE test_products (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, category_id INTEGER NOT NULL, FOREIGN KEY(category_id) REFERENCES test_categories(id))');

$db->exec("INSERT INTO test_categories (id, name) VALUES (1, 'UNi TEtS')");

$stmt = $db->prepare('INSERT INTO test_products (name, category_id) VALUES (:name, :category_id)');
$stmt->execute([':name' => 'Test Product', ':category_id' => 1]);

$id = (int)$db->lastInsertId();
echo $id;
?>
--EXPECT--
