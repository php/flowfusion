--TEST--
Translated seed (inferredbugs-csharp: Mongo2Go/2)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_pattern_' . uniqid('', true);
$lock = $dir . DIRECTORY_SEPARATOR . 'mongod.lock';
@mkdir($dir, 0777, true);
file_put_contents($lock, '');
$running = false;
$portTaken = false;
if ($running) {
    $state = 'already_running';
} elseif ($portTaken) {
    $state = 'port_taken';
} else {
    $binaries = null;
    foreach ([$dir . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Mongo2Go' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'mongodb-win32-i386' . DIRECTORY_SEPARATOR . 'bin', $dir . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'mongodb-win32-i386' . DIRECTORY_SEPARATOR . 'bin'] as $p) {
        if (is_dir($p)) {
            $binaries = $p;
            break;
        }
    }
    if ($binaries === null) {
        $binaries = $dir;
    }
    @mkdir($dir . DIRECTORY_SEPARATOR . 'data', 0777, true);
    @unlink($lock);
    $state = 'running';
}
if ($state === 'running') {
    @unlink($lock);
    @rmdir($dir . DIRECTORY_SEPARATOR . 'data');
}
@rmdir($dir);
echo $state, PHP_EOL;
?>
--EXPECT--
