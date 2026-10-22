--TEST--
Translated seed (inferredbugs-csharp: serilog-sinks-mssqlserver/3)
--FILE--
<?php
declare(strict_types=1);

$cfg = ['connectionString' => 'name', 'tableName' => 't', 'appConfiguration' => ['connectionString' => 'app'], 'columnOptionsSection' => ['x' => 1], 'schemaName' => 'dbo'];
$conn = $cfg['connectionString'];
$col = ['default' => true];

if (isset($cfg['systemSection'])) {
    $col = array_merge($col, $cfg['systemSection']);
    $conn = $cfg['systemConnection'] ?? $conn;
    if ($cfg['appConfiguration'] !== null || $cfg['columnOptionsSection'] !== null) {
        fwrite(STDERR, "Warning: Both configuration sources are being applied.\n");
    }
}

if ($cfg['appConfiguration'] !== null || $cfg['columnOptionsSection'] !== null) {
    $conn = $cfg['appConfiguration']['connectionString'] ?? $conn;
    $col = array_merge($col, $cfg['columnOptionsSection'] ?? []);
}

$sink = [
    'connectionString' => $conn,
    'tableName' => $cfg['tableName'],
    'schemaName' => $cfg['schemaName'],
    'columnOptions' => $col
];

echo $sink['connectionString'], "\n";
echo $sink['tableName'], "\n";
echo $sink['schemaName'], "\n";
echo count($sink['columnOptions']), "\n";
?>
--EXPECT--
