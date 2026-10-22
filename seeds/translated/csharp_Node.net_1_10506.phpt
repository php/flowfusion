--TEST--
Translated seed (inferredbugs-csharp: Node.net/1)
--FILE--
<?php
$dir = $argc === 2 ? dirname($argv[1]) : '';
$base = getcwd() . $dir;
$file = basename($argv[1]);
$path = $base . DIRECTORY_SEPARATOR . $file . '.js';
if (!is_file($path)) {
    $path = $base . DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'index.js';
}
echo file_get_contents($path);
?>
--EXPECT--
