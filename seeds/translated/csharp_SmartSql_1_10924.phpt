--TEST--
Translated seed (inferredbugs-csharp: SmartSql/1)
--FILE--
<?php
$pdo = new PDO('mysql:host=localhost;dbname=SmartSqlTestDB;charset=utf8mb4', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
]);

$pdo->exec("DROP TABLE IF EXISTS t_user");
$pdo->exec("CREATE TABLE t_user (Id INT NOT NULL, UserName VARCHAR(255) NOT NULL)");

$rows = [
    [1, '1'],
    [2, '2'],
];

$tmp = tempnam(sys_get_temp_dir(), 'bulk_');
$f = fopen($tmp, 'wb');
foreach ($rows as $r) {
    fwrite($f, $r[0] . "\t" . $r[1] . "\n");
}
fclose($f);

$pdo->exec("SET SESSION local_infile = 1");
$stmt = $pdo->prepare("LOAD DATA LOCAL INFILE " . $pdo->quote($tmp) . " INTO TABLE t_user FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\n' (Id, UserName)");
$stmt->execute();

unlink($tmp);
?>
--EXPECT--
