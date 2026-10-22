--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCore.Taos/1)
--FILE--
<?php

function main() {
    $database = "db_" . date("YmdHis");
    $connectionString = "taos:dbname={$database};user=root;password=taosdata;port=6030";

    $pdo = new PDO($connectionString);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE {$database}");
    $pdo->exec("USE {$database}");
    $pdo->exec("CREATE TABLE t (ts TIMESTAMP, cdata INT)");

    $timestamp = (int)(microtime(true) * 1000);
    $pdo->exec("INSERT INTO t VALUES (FROM_UNIXTIME($timestamp/1000), 10)");

    sleep(1);
    $timestamp = (int)(microtime(true) * 1000);
    $stmt = $pdo->prepare("INSERT INTO t VALUES (?, ?)");
    $stmt->execute([FROM_UNIXTIME($timestamp/1000), 1111]);

    $pdo->exec("INSERT INTO t VALUES (FROM_UNIXTIME($timestamp/1000), 10)");

    $stmt = $pdo->query("SELECT * FROM t");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($results);
    
    $pdo->exec("DROP TABLE t");
    $pdo->exec("DROP DATABASE {$database}");
}

main();
--EXPECT--
