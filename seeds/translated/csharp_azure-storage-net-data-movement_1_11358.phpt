--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/1)
--FILE--
<?php
$src = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_subdir_transfer_src_' . uniqid('', true);
$dst = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_subdir_transfer_dst_' . uniqid('', true);
@mkdir($src, 0777, true);
@mkdir($dst, 0777, true);
@mkdir($src . DIRECTORY_SEPARATOR . 'a', 0777, true);
@mkdir($src . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b', 0777, true);
file_put_contents($src . DIRECTORY_SEPARATOR . 'root.txt', 'x');
file_put_contents($src . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'f1.txt', 'y');
file_put_contents($src . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'f2.txt', 'z');

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($it as $path => $info) {
    $rel = substr($path, strlen($src) + 1);
    $target = $dst . DIRECTORY_SEPARATOR . $rel;
    if ($info->isDir()) {
        if (!is_dir($target)) {
            @mkdir($target, 0777, true);
        }
        clearstatcache(true, $target);
        if (!is_dir($target)) {
            throw new RuntimeException('mkdir failed');
        }
    } else {
        $parent = dirname($target);
        if (!is_dir($parent)) {
            @mkdir($parent, 0777, true);
        }
        if (!copy($path, $target)) {
            throw new RuntimeException('copy failed');
        }
        clearstatcache(true, $target);
        if (!is_file($target)) {
            throw new RuntimeException('file missing');
        }
    }
}

$check = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dst, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($check as $path => $info) {
    if ($info->isFile()) {
        file_get_contents($path);
    }
}
?>
--EXPECT--
