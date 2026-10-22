--TEST--
Translated seed (inferredbugs-csharp: PKHeX-Plugins/1)
--FILE--
<?php
$dest = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'eventsgallery_' . bin2hex(random_bytes(4));
mkdir($dest);
$root = $dest . DIRECTORY_SEPARATOR . 'EventsGallery-master';
mkdir($root);
mkdir($root . DIRECTORY_SEPARATOR . 'Unreleased', 0777, true);
mkdir($root . DIRECTORY_SEPARATOR . 'Extras', 0777, true);
file_put_contents($root . DIRECTORY_SEPARATOR . '.gitignore', '');
file_put_contents($root . DIRECTORY_SEPARATOR . 'README.md', '');
rmdir($root . DIRECTORY_SEPARATOR . 'Unreleased');
rmdir($root . DIRECTORY_SEPARATOR . 'Extras');
unlink($root . DIRECTORY_SEPARATOR . '.gitignore');
unlink($root . DIRECTORY_SEPARATOR . 'README.md');
rmdir($root);
rmdir($dest);
--EXPECT--
