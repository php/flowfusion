--TEST--
Translated seed (inferredbugs-csharp: nbfc/1)
--FILE--
<?php
$path = '/dev/port';
$fp = @fopen($path, 'c+b');
if ($fp) {
    flock($fp, LOCK_EX);
    fseek($fp, 0, SEEK_SET);
    fwrite($fp, chr(0));
    fseek($fp, 0, SEEK_SET);
    fread($fp, 1);
    flock($fp, LOCK_UN);
    fclose($fp);
}
?>
--EXPECT--
