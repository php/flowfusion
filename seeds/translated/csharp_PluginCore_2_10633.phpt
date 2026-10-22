--TEST--
Translated seed (inferredbugs-csharp: PluginCore/2)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'PluginCoreAdmin_' . bin2hex(random_bytes(4));
@mkdir($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin', 0777, true);
file_put_contents($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . 'index.html', '');
file_put_contents($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . 'a.txt', str_repeat('x', 1));

$paths = [
    $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin',
    $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . '.',
    $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'PluginCoreAdmin',
];

foreach ($paths as $p) {
    $real = realpath($p);
    if ($real !== false) {
        $it = new FilesystemIterator($real, FilesystemIterator::SKIP_DOTS);
        foreach ($it as $f) {
            $name = $f->getFilename();
            $size = $f->getSize();
            $isFile = $f->isFile();
            if ($isFile && $size >= 0 && $name !== '') {
                $c = file_get_contents($f->getPathname());
                if ($c === false) {
                    throw new RuntimeException('read failed');
                }
            }
        }
    }
}

$dir = new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS);
$iter = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
foreach ($iter as $item) {
    $path = $item->getPathname();
    if ($item->isDir()) {
        clearstatcache(true, $path);
    } else {
        stat($path);
        filesize($path);
    }
}

unlink($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . 'a.txt');
unlink($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin' . DIRECTORY_SEPARATOR . 'index.html');
rmdir($root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin');
rmdir($root);
?>
--EXPECT--
