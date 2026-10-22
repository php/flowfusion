--TEST--
Translated seed (inferredbugs-csharp: Threenine.Data/3)
--FILE--
<?php
$db = new SQLite3(':memory:');
$db->exec('CREATE TABLE categories (id INTEGER PRIMARY KEY, name TEXT)');
$db->exec('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, category_id INTEGER, FOREIGN KEY(category_id) REFERENCES categories(id))');
$db->exec("INSERT INTO categories (id, name) VALUES (1, 'UNi TEtS')");
$stmt = $db->prepare('INSERT INTO products (name, category_id) VALUES (:name, :category_id)');
$stmt->bindValue(':name', 'Test Product', SQLITE3_TEXT);
$stmt->bindValue(':category_id', 1, SQLITE3_INTEGER);
$stmt->execute();
$id = $db->lastInsertRowID();
if ($id < 1) {
    throw new Exception('Insert failed');
}
echo $id;
?>
--EXPECT--
