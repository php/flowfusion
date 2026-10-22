--TEST--
Translated seed (inferredbugs-csharp: serilog-sinks-mssqlserver/1)
--FILE--
<?php
$cfg = ['section' => ['conn' => 'cfg_conn', 'cols' => ['a' => 1]]];
$section = $cfg['section'] ?? null;
$conn = 'arg_conn';
$cols = ['x' => 0];

if ($section !== null) {
    $conn = $section['conn'] ?? $conn;
    $cols = array_merge($cols, $section['cols'] ?? []);
    if ($cfg !== null || $section !== null) {
        fwrite(STDERR, "Warning: both configuration sources are being applied.\n");
    }
}

if ($cfg !== null || $section !== null) {
    $conn = $cfg['conn'] ?? $conn;
    $cols = array_merge($cols, $cfg['cols'] ?? []);
}

echo $conn, "\n";
echo json_encode($cols, JSON_UNESCAPED_SLASHES), "\n";
?>
--EXPECT--
