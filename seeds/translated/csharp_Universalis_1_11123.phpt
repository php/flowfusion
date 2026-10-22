--TEST--
Translated seed (inferredbugs-csharp: Universalis/1)
--FILE--
<?php
$db = new PDO('mysql:host=localhost;dbname=universalis_test;charset=utf8mb4', 'dalamud', 'dalamud', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$db->exec('DROP TABLE IF EXISTS currently_shown');
$db->exec('CREATE TABLE currently_shown (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shown_at DATETIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$expected = [
    'currently_shown' => [
        'id' => 'int',
        'shown_at' => 'datetime',
        'title' => 'varchar(255)',
        'is_active' => 'tinyint(1)',
    ],
];

$actual = [];
$stmt = $db->query("SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, COLUMN_TYPE
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                    ORDER BY TABLE_NAME, ORDINAL_POSITION");
foreach ($stmt as $row) {
    $table = $row['TABLE_NAME'];
    $column = $row['COLUMN_NAME'];
    $type = strtolower($row['COLUMN_TYPE']);
    $actual[$table][$column] = $type;
}

foreach ($expected as $table => $columns) {
    foreach ($columns as $column => $type) {
        if (!isset($actual[$table][$column])) {
            throw new RuntimeException("Missing $table.$column");
        }
        if (strpos($actual[$table][$column], $type) !== 0) {
            throw new RuntimeException("Type mismatch for $table.$column");
        }
    }
}

echo "ok\n";
--EXPECT--
