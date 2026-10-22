--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/10)
--FILE--
<?php
$dir = sys_get_temp_dir() . '/php_prepared_stmt_bug_' . uniqid('', true);
mkdir($dir);
$db = new SQLite3($dir . '/test.db');
$db->busyTimeout(1000);
$db->exec('PRAGMA journal_mode=WAL');
$db->exec('CREATE TABLE IF NOT EXISTS test(k TEXT PRIMARY KEY, i INTEGER)');
$db->exec("INSERT OR REPLACE INTO test(k, i) VALUES ('123', 17)");
$db->exec("INSERT OR REPLACE INTO test(k, i) VALUES ('124', 18)");

$stmt = $db->prepare('SELECT i FROM test WHERE k = ?');
$stmt->bindValue(1, '123', SQLITE3_TEXT);
$res = $stmt->execute();
$row = $res->fetchArray(SQLITE3_ASSOC);
$res->finalize();
$stmt->reset();
$stmt->clear();
$stmt->bindValue(1, '124', SQLITE3_TEXT);
$res = $stmt->execute();
$row = $res->fetchArray(SQLITE3_ASSOC);
$res->finalize();
$db->close();
?>
--EXPECT--
