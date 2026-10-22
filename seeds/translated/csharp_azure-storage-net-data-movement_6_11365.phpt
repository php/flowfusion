--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net-data-movement/6)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_subdir_' . bin2hex(random_bytes(4));
@mkdir($dir);
@mkdir($dir . DIRECTORY_SEPARATOR . 'a');
@mkdir($dir . DIRECTORY_SEPARATOR . 'b');
file_put_contents($dir . DIRECTORY_SEPARATOR . 'f1.txt', 'x');
file_put_contents($dir . DIRECTORY_SEPARATOR . 'f2.txt', 'y');

$it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

$seen = [];
foreach ($ri as $path => $info) {
    $rel = substr($path, strlen($dir) + 1);
    $token = ['path' => $rel, 'offset' => strlen($rel)];
    $seen[] = $token;
    if ($info->isDir()) {
        @mkdir($dir . DIRECTORY_SEPARATOR . $rel . DIRECTORY_SEPARATOR . 'child');
    } else {
        $parent = dirname($dir . DIRECTORY_SEPARATOR . $rel);
        if (!is_dir($parent)) {
            @mkdir($parent, 0777, true);
        }
        file_put_contents($parent . DIRECTORY_SEPARATOR . basename($rel) . '.copy', '');
    }
}

foreach ($seen as $t) {
    if ($t['offset'] < 0) {
        echo '';
    }
}
?>
--EXPECT--
