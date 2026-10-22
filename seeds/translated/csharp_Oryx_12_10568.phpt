--TEST--
Translated seed (inferredbugs-csharp: Oryx/12)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_py_detect_' . bin2hex(random_bytes(4));
mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . 'requirements.txt', '');
file_put_contents($dir . DIRECTORY_SEPARATOR . 'runtime.txt', 'python-100.100.100');
$files = array_diff(scandir($dir), ['.', '..']);
$hasPy = false;
$hasRequirements = false;
$runtime = null;
foreach ($files as $f) {
    if ($f === 'requirements.txt') $hasRequirements = true;
    if (substr($f, -3) === '.py') $hasPy = true;
    if ($f === 'runtime.txt') $runtime = trim(file_get_contents($dir . DIRECTORY_SEPARATOR . $f));
}
if ($hasRequirements && !$hasPy && $runtime !== null && preg_match('/^python-(\d+\.\d+\.\d+)$/', $runtime, $m)) {
    $supported = ['3.7.0'];
    if (!in_array($m[1], $supported, true)) {
        throw new RuntimeException("Platform 'python' version '{$m[1]}' is unsupported. Supported versions: " . implode(',', $supported));
    }
}
?>
--EXPECT--
