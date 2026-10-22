--TEST--
Translated seed (inferredbugs-java: YCSB/13)
--FILE--
<?php
$table = "usertable";
$defaultRowKey = "user1";

$pdo = new PDO('sqlite::memory:');
$pdo->exec("CREATE TABLE $table (id TEXT PRIMARY KEY, field0 TEXT, field1 TEXT)");

function insertRow($pdo, $table, $rowKey) {
    $stmt = $pdo->prepare("INSERT INTO $table (id, field0, field1) VALUES (?, ?, ?)");
    $stmt->execute([$rowKey, "value1", "value2"]);
}

function readRow($pdo, $table, $rowKey) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$rowKey]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

insertRow($pdo, $table, $defaultRowKey);

$result = readRow($pdo, $table, $defaultRowKey);
if ($result) {
    echo "Row Found: " . json_encode($result) . "\n";
    $fieldCount = count($result);
    echo "Field Count: $fieldCount\n";
} else {
    echo "Row Not Found\n";
}
--EXPECT--
