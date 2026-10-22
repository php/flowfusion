--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCore.Taos/5)
--FILE--
<?php

$database = "db_" . date("YmdHis");
$pdo = new PDO("mysql:host=taos;dbname=$database", 'root', 'taosdata', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$pdo->exec("CREATE DATABASE $database");
$pdo->exec("CREATE TABLE {$database}.t (ts TIMESTAMP, cdata INT)");
$pdo->exec("INSERT INTO {$database}.t VALUES (NOW(), 10)");

sleep(1);
$pdo->prepare("INSERT INTO {$database}.t (ts, cdata) VALUES (?, ?)")
    ->execute([date("Y-m-d H:i:s", time()), 1111]);

$pdo->exec("INSERT INTO {$database}.t VALUES (NOW(), 10)");

$results = $pdo->query("SELECT * FROM {$database}.t");
foreach ($results as $row) {
    echo "ts: " . $row['ts'] . ", cdata: " . $row['cdata'] . "\n";
}

$pdo->exec("CREATE TABLE datas (reportTime TIMESTAMP, type INT, bufferedEnd BOOLEAN, address CHAR(64), parameter CHAR(64), value CHAR(64))");
$pdo->exec("INSERT INTO datas (reportTime, type, bufferedEnd, address, parameter, value) VALUES (1608173534840, 2, false, 'Channel1.窑.烟囱温度', '烟囱温度', '122.00')");

$resultsDatas = $pdo->query("SELECT reportTime, type, bufferedEnd, address, parameter, value FROM datas LIMIT 100");
foreach ($resultsDatas as $row) {
    echo "reportTime: " . $row['reportTime'] . ", type: " . $row['type'] . "\n";
}

$pdo->exec("CREATE TABLE meters (ts TIMESTAMP, current FLOAT, voltage INT, phase FLOAT)");
$pdo->exec("CREATE TABLE d1001 SELECT * FROM meters WHERE 1=0");
$pdo->exec("INSERT INTO d1001 (ts, current, voltage, phase) VALUES (NOW(), 10.2, 219, 0.32)");
$pdo->exec("DROP TABLE {$database}.t");
$pdo->exec("DROP DATABASE $database");

$pdo = null;
--EXPECT--
