--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCore.Taos/4)
--FILE--
<?php

$database = "db_" . date("YmdHis");
$pdo = new PDO('mysql:host=localhost;port=6030;dbname=' . $database, 'root', 'taosdata');

// Create database and table
$pdo->exec("CREATE DATABASE {$database}");
$pdo->exec("CREATE TABLE {$database}.t (ts TIMESTAMP, cdata INT)");

// Insert first row
$ts = (int)(microtime(true) * 1000);
$pdo->exec("INSERT INTO {$database}.t VALUES (FROM_UNIXTIME($ts / 1000), 10)");

// Insert with parameters
$stmt = $pdo->prepare("INSERT INTO {$database}.t VALUES (?, ?)");
sleep(1);
$ts = (int)(microtime(true) * 1000);
$stmt->execute([date('Y-m-d H:i:s', $ts / 1000), 1111]);

// Insert another row
$ts = (int)(microtime(true) * 1000);
$pdo->exec("INSERT INTO {$database}.t VALUES (FROM_UNIXTIME($ts / 1000), 10)");

// Select data
$stmt_select = $pdo->query("SELECT * FROM {$database}.t");
$result = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
var_dump($result);

// Create another table
$pdo->exec("CREATE TABLE datas (reportTime TIMESTAMP, type INT, bufferedEnd BOOL, address NCHAR(64), parameter NCHAR(64), value NCHAR(64)) TAGS (boxCode NCHAR(64), machineId INT)");
$pdo->exec("INSERT INTO data_history_67 USING datas TAGS ('mongo', 67) VALUES (1608173534840, 2, FALSE, 'Channel1.窑.烟囱温度', '烟囱温度', '122.00')");

// Select from the new table
$stmt_datas = $pdo->query("SELECT reportTime, type, bufferedEnd, address, parameter, value FROM {$database}.data_history_67 LIMIT 100");
$result_datas = $stmt_datas->fetchAll(PDO::FETCH_ASSOC);
var_dump($result_datas);

// Drop table and database
$pdo->exec("DROP TABLE {$database}.t");
$pdo->exec("DROP DATABASE {$database}");

?>
--EXPECT--
