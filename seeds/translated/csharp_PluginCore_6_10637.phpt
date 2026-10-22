--TEST--
Translated seed (inferredbugs-csharp: PluginCore/6)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'plugincore_' . uniqid('', true);
$admin = $root . DIRECTORY_SEPARATOR . 'PluginCore' . DIRECTORY_SEPARATOR . 'Admin';
$plugins = $root . DIRECTORY_SEPARATOR . 'Plugins';
@mkdir($admin, 0777, true);
@mkdir($plugins, 0777, true);
file_put_contents($admin . DIRECTORY_SEPARATOR . 'index.html', 'admin');
file_put_contents($plugins . DIRECTORY_SEPARATOR . 'a.txt', 'plugin');

$paths = [$admin, $plugins, $admin . DIRECTORY_SEPARATOR . '.', $plugins . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Plugins'];
foreach ($paths as $p) {
    $real = realpath($p);
    if ($real !== false) {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($real, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            $f->getPathname();
        }
    }
}

$files = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    $files[] = $f->getFilename();
}
sort($files);
echo implode(',', $files), PHP_EOL;
?>
--EXPECT--
