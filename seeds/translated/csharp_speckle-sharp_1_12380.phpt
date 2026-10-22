--TEST--
Translated seed (inferredbugs-csharp: speckle-sharp/1)
--FILE--
<?php
$basePath = sys_get_temp_dir();
$app = 'Speckle';
$scope = 'Objects';
$dir = $basePath . DIRECTORY_SEPARATOR . $app;
@mkdir($dir, 0777, true);
$dbPath = $dir . DIRECTORY_SEPARATOR . $scope . '.db';

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('CREATE TABLE IF NOT EXISTS objects(hash TEXT PRIMARY KEY, content TEXT) WITHOUT ROWID');

$buffer = [];
$cur = 0;
$max = 5000000;

$objects = [
    ["a", str_repeat("x", 1)],
    ["b", str_repeat("é", 2)],
    ["c", str_repeat("😀", 3)],
    ["a", "dup"],
];

foreach ($objects as [$hash, $content]) {
    $cur += strlen($content);
    $buffer[$hash] = $content;
    if ($cur > $max) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO objects(hash, content) VALUES(:hash, :content)');
        foreach ($buffer as $h => $c) {
            $stmt->execute([':hash' => $h, ':content' => gzcompress($c)]);
        }
        $pdo->commit();
        $buffer = [];
        $cur = 0;
    }
}

if ($buffer) {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO objects(hash, content) VALUES(:hash, :content)');
    foreach ($buffer as $h => $c) {
        $stmt->execute([':hash' => $h, ':content' => gzcompress($c)]);
    }
    $pdo->commit();
}

$stmt = $pdo->prepare('SELECT content FROM objects WHERE hash = :hash LIMIT 1');
$stmt->execute([':hash' => 'b']);
$row = $stmt->fetch(PDO::FETCH_NUM);
if ($row !== false) {
    gzuncompress($row[0]);
}

$pdo = null;
?>
--EXPECT--
