--TEST--
Translated seed (inferredbugs-csharp: RPGCore/4)
--FILE--
<?php
$root = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_fs_archive_' . bin2hex(random_bytes(4));
@mkdir($root, 0777, true);
@mkdir($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b', 0777, true);
file_put_contents($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'x.txt', "1");
rename($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'x.txt', $root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'y.txt');
file_put_contents($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'y.txt', "2");
@unlink($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b' . DIRECTORY_SEPARATOR . 'y.txt');
@rmdir($root . DIRECTORY_SEPARATOR . 'a' . DIRECTORY_SEPARATOR . 'b');
@rmdir($root . DIRECTORY_SEPARATOR . 'a');
@rmdir($root);
?>
--EXPECT--
