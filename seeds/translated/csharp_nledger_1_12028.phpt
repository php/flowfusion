--TEST--
Translated seed (inferredbugs-csharp: nledger/1)
--FILE--
<?php
$argv = $_SERVER['argv'] ?? [];
$path = $argv[1] ?? __FILE__;
$path = realpath($path) ?: $path;
$dir = dirname($path);
set_include_path($dir . PATH_SEPARATOR . get_include_path());
if (!in_array($dir, explode(PATH_SEPARATOR, get_include_path()), true)) {
    set_include_path($dir . PATH_SEPARATOR . get_include_path());
}
$GLOBALS['__file__'] = $path;
if (is_file($path)) {
    $code = file_get_contents($path);
    if ($code !== false) {
        eval('?>' . $code);
    }
}
?>
--EXPECT--
