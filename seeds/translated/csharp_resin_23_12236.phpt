--TEST--
Translated seed (inferredbugs-csharp: resin/23)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'resin_tests' . DIRECTORY_SEPARATOR;
if (is_dir($root)) {
    foreach (glob($root . '*', GLOB_ONLYDIR) as $dir) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $path) {
            if ($path->isDir()) {
                @rmdir($path->getPathname());
            } else {
                @unlink($path->getPathname());
            }
        }
        @rmdir($dir);
    }
}
?>
--EXPECT--
