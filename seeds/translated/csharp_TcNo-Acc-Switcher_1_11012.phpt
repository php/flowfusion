--TEST--
Translated seed (inferredbugs-csharp: TcNo-Acc-Switcher/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_shortcut_bug_' . uniqid();
@mkdir($dir, 0777, true);
$path = $dir . DIRECTORY_SEPARATOR . 'a.txt';
file_put_contents($path, "x");
$exists = file_exists($path);
$parent = dirname($path);
$files = glob($parent . DIRECTORY_SEPARATOR . '*');
if ($exists && count($files) > 0) {
    unlink($path);
}
if (is_dir($dir) && count(glob($dir . DIRECTORY_SEPARATOR . '*')) === 0) {
    rmdir($dir);
}
echo "";
?>
--EXPECT--
