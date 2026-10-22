--TEST--
Translated seed (inferredbugs-csharp: PluginCore/5)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'PluginCoreAdmin';
if (!is_dir($root)) {
    mkdir($root, 0777, true);
}
$file = $root . DIRECTORY_SEPARATOR . 'x.txt';
file_put_contents($file, "ok");
$path = realpath($root);
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->isFile()) {
        $c = file_get_contents($f->getPathname());
        if ($c !== false) {
            strlen($c);
        }
    }
}
$req = '/PluginCore/Admin';
$prefix = '/PluginCore';
if (strncmp($req, $prefix, strlen($prefix)) === 0) {
    $rest = substr($req, strlen($prefix));
    if ($rest !== '' && $rest[0] === '/') {
        $rest = substr($rest, 1);
    }
}
echo "done\n";
?>
--EXPECT--
