--TEST--
Translated seed (inferredbugs-java: YCSB/31)
--FILE--
<?php
$values = [];
$key = "example_key";
$table = "example_table";

// Simulate large insert
for ($i = 0; $i < 1000; $i++) {
    $values["field" . $i] = str_repeat("A", 10000); // Large values
}

// Insert into database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=test', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $columns = implode(", ", array_keys($values));
    $placeholders = implode(", ", array_fill(0, count($values), "?"));
    $stmt = $pdo->prepare("INSERT INTO {$table} ($columns) VALUES ($placeholders)");
    $stmt->execute(array_values($values));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
--EXPECT--
