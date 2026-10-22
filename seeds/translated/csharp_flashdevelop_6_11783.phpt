--TEST--
Translated seed (inferredbugs-csharp: flashdevelop/6)
--FILE--
<?php
$args = array_slice($argv, 1);
$lock = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'appman.lock';
$fh = @fopen($lock, 'c');

if ($fh && flock($fh, LOCK_EX | LOCK_NB)) {
    flock($fh, LOCK_UN);
    fclose($fh);
} elseif (!in_array('-minimized', $args, true)) {
    echo "AppMan is already running.\n";
}
?>
--EXPECT--
