--TEST--
Translated seed (inferredbugs-csharp: serilog-sinks-mssqlserver/2)
--FILE--
<?php
$config = ['MSSqlServerSettingsSection' => ['conn' => 'Server=.;Database=demo;', 'cols' => ['a' => 1]]];
$section = $config['MSSqlServerSettingsSection'] ?? null;
$conn = 'DefaultConnection';
$cols = ['default' => true];
if (is_array($section)) {
    $cols = array_merge($cols, $section['cols'] ?? []);
    $conn = $section['conn'] ?? $conn;
    if (isset($config['app']) || isset($config['columnOptionsSection'])) {
        fwrite(STDERR, "Warning: Both configuration sources are being applied.\n");
    }
}
if (isset($config['app']) || isset($config['columnOptionsSection'])) {
    $conn = $config['app']['conn'] ?? $conn;
    $cols = array_merge($cols, $config['columnOptionsSection'] ?? []);
}
$batchPostingLimit = 10;
$period = $config['period'] ?? 0.5;
$tableName = 'Logs';
$schemaName = 'dbo';
$sink = [
    'conn' => $conn,
    'table' => $schemaName . '.' . $tableName,
    'batch' => $batchPostingLimit,
    'period' => $period,
    'cols' => $cols,
];
echo json_encode($sink, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
