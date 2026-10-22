--TEST--
Translated seed (inferredbugs-csharp: LiteDB/1)
--FILE--
<?php
$dbFile = tempnam(sys_get_temp_dir(), 'lite');
file_put_contents($dbFile, 'not a database');

try {
    $db = new SQLite3($dbFile);
    $db->querySingle('SELECT name FROM sqlite_master LIMIT 1');
    $db->close();
} catch (Throwable $e) {
}

@unlink($dbFile);

$dbFile = tempnam(sys_get_temp_dir(), 'lite');
$db = new SQLite3($dbFile);
$db->exec('CREATE TABLE t (id INTEGER PRIMARY KEY, v TEXT)');
$db->exec("INSERT INTO t (v) VALUES ('a'), ('b')");
$res = $db->query('SELECT id, v FROM t ORDER BY id');

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

$db->close();
@unlink($dbFile);
?>
--EXPECT--
