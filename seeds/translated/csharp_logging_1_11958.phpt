--TEST--
Translated seed (inferredbugs-csharp: logging/1)
--FILE--
<?php
$cfg = ['File' => ['Path' => 'php://temp', 'Append' => 'true'], 'LogLevel' => ['Default' => 'warning', 'System' => 'error']];
$file = $cfg['File'] ?? null;
if (!$file || !isset($file['Path']) || trim($file['Path']) === '') {
    exit;
}
$append = true;
if (isset($file['Append']) && $file['Append'] !== '') {
    $append = filter_var($file['Append'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($append === null) $append = true;
}
$levels = [];
$hasFilter = false;
if (isset($cfg['LogLevel']) && is_array($cfg['LogLevel'])) {
    foreach ($cfg['LogLevel'] as $k => $v) {
        $levels[$k] = strtolower((string)$v);
        $hasFilter = true;
    }
}
$h = fopen($file['Path'], $append ? 'a' : 'w');
if ($h === false) {
    exit;
}
if ($hasFilter) {
    foreach ($levels as $k => $v) {
        fwrite($h, $k . ':' . $v . PHP_EOL);
    }
}
fwrite($h, "test\n");
fclose($h);
--EXPECT--
