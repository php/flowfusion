--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/13)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_bug_pattern_' . getmypid() . '_' . mt_rand();
mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . 'a.txt', str_repeat("A", 8192));
mkdir($dir . DIRECTORY_SEPARATOR . 'sub');
file_put_contents($dir . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'b.txt', str_repeat("B", 16384));

$paths = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
foreach ($paths as $p) {
    if ($p->isDir()) {
        continue;
    }
    $fp = fopen($p->getPathname(), 'rb');
    $sum = 0;
    while (!feof($fp)) {
        $buf = fread($fp, 8192);
        $sum += strlen($buf);
    }
    fclose($fp);
    echo $p->getFilename() . " " . $sum . " " . filemtime($p->getPathname()) . PHP_EOL;
}

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
foreach ($it as $p) {
    $p->isDir() ? rmdir($p->getPathname()) : unlink($p->getPathname());
}
rmdir($dir);
echo "--DONE--" . PHP_EOL;
?>
--EXPECT--
