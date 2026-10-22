--TEST--
Translated seed (inferredbugs-csharp: ScheduleMasterCore/1)
--FILE--
<?php
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'Plugins';
if (!is_dir($dir)) mkdir($dir, 0777, true);
$zipPath = $dir . DIRECTORY_SEPARATOR . 'test.zip';
$pluginPath = $dir . DIRECTORY_SEPARATOR . 'test';
if (is_dir($pluginPath)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pluginPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $p) $p->isDir() ? rmdir($p->getPathname()) : unlink($p->getPathname());
    rmdir($pluginPath);
}
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $zip->addFromString('a.txt', 'a');
    $zip->addFromString('nested/b.txt', 'b');
    $zip->close();
}
$zip = new ZipArchive();
if ($zip->open($zipPath) === true) {
    if (!is_dir($pluginPath)) mkdir($pluginPath, 0777, true);
    $zip->extractTo($pluginPath);
    $zip->close();
}
if (file_exists($zipPath)) unlink($zipPath);
echo "ok\n";
?>
--EXPECT--
