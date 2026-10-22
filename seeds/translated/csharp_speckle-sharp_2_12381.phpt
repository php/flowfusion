--TEST--
Translated seed (inferredbugs-csharp: speckle-sharp/2)
--FILE--
<?php
$db = new SQLite3(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'objects_' . uniqid('', true) . '.db');
$db->exec('PRAGMA journal_mode=MEMORY;');
$db->exec('PRAGMA synchronous=OFF;');
$db->exec('PRAGMA count_changes=OFF;');
$db->exec('PRAGMA temp_store=MEMORY;');
$db->exec('CREATE TABLE IF NOT EXISTS objects(hash TEXT PRIMARY KEY, content TEXT) WITHOUT ROWID;');

$buffer = [];
$curr = 0;
$max = 5000000;

$saveObject = function(string $hash, string $serializedObject) use (&$buffer, &$curr, $max, $db) {
    $curr += strlen($serializedObject);
    $buffer[$hash] = $serializedObject;
    if ($curr > $max) {
        $db->exec('BEGIN');
        $stmt = $db->prepare('INSERT OR IGNORE INTO objects(hash, content) VALUES(:hash, :content)');
        foreach ($buffer as $k => $v) {
            $stmt->bindValue(':hash', $k, SQLITE3_TEXT);
            $stmt->bindValue(':content', gzcompress($v), SQLITE3_BLOB);
            $stmt->execute();
        }
        $db->exec('COMMIT');
        $buffer = [];
        $curr = 0;
    }
};

$saveObject('aa', str_repeat('x', 1024));
$saveObject('bb', str_repeat('y', 1024));
$saveObject('aa', str_repeat('z', 1024));

$db->exec('BEGIN');
$stmt = $db->prepare('INSERT OR IGNORE INTO objects(hash, content) VALUES(:hash, :content)');
foreach ([['cc', 'hello'], ['dd', 'world']] as [$h, $c]) {
    $stmt->bindValue(':hash', $h, SQLITE3_TEXT);
    $stmt->bindValue(':content', gzcompress($c), SQLITE3_BLOB);
    $stmt->execute();
}
$db->exec('COMMIT');

$stmt = $db->prepare('SELECT content FROM objects WHERE hash = :hash LIMIT 1');
$stmt->bindValue(':hash', 'aa', SQLITE3_TEXT);
$res = $stmt->execute();
$row = $res->fetchArray(SQLITE3_NUM);
if ($row !== false) {
    gzuncompress($row[0]);
}

$db->close();
?>
--EXPECT--
