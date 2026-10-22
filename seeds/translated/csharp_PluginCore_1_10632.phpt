--TEST--
Translated seed (inferredbugs-csharp: PluginCore/1)
--FILE--
<?php
$root = getcwd();
$dir = $root . DIRECTORY_SEPARATOR . 'PluginCoreAdmin';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$file = $dir . DIRECTORY_SEPARATOR . 'index.html';
if (!file_exists($file)) {
    file_put_contents($file, "<!doctype html><html><body>ok</body></html>");
}
$base = rtrim(str_replace('\\', '/', $root), '/') . '/';
$path = $base . 'PluginCoreAdmin';
$real = realpath($path);
$req = '/PluginCore/Admin';
$static = $real !== false && is_dir($real) ? $real : $path;
$default = is_file($static . DIRECTORY_SEPARATOR . 'index.html') ? file_get_contents($static . DIRECTORY_SEPARATOR . 'index.html') : '';
echo $req . "\n";
echo $default . "\n";
?>
--EXPECT--
