--TEST--
Translated seed (inferredbugs-csharp: slskd/5)
--FILE--
<?php
$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_shared_cache_' . uniqid('', true);
@mkdir($base);
$share = $base . DIRECTORY_SEPARATOR . 'parent' . DIRECTORY_SEPARATOR . 'child';
@mkdir($share, 0777, true);
file_put_contents($share . DIRECTORY_SEPARATOR . 'a.txt', 'x');
@mkdir($base . DIRECTORY_SEPARATOR . 'parent' . DIRECTORY_SEPARATOR . 'empty', 0777, true);

$dirs = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
foreach ($it as $path => $info) {
    if ($info->isDir()) $dirs[$path] = true;
}

$files = [];
foreach (array_keys($dirs) as $d) {
    foreach (glob($d . DIRECTORY_SEPARATOR . '*') ?: [] as $f) {
        if (is_file($f)) $files[$f] = true;
    }
}

$browse = [];
foreach (array_keys($dirs) as $d) $browse[$d] = [];
foreach (array_keys($files) as $f) {
    $k = dirname($f);
    $browse[$k][] = basename($f);
}
ksort($browse);
foreach ($browse as $k => $v) sort($v);

echo "";
?>
--EXPECT--
