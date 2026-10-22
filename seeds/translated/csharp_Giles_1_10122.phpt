--TEST--
Translated seed (inferredbugs-csharp: Giles/1)
--FILE--
<?php
echo "Giles - your own personal watcher\n";
fgets(STDIN);
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testableteststuff';
@mkdir($dir);
foreach (glob($dir . DIRECTORY_SEPARATOR . '*.cs') as $f) {
    clearstatcache(true, $f);
}
?>
--EXPECT--
