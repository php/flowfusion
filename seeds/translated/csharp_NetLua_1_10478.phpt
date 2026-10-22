--TEST--
Translated seed (inferredbugs-csharp: NetLua/1)
--FILE--
<?php
$stdin = fopen("php://stdin", "r");
$stdout = fopen("php://stdout", "w");
$stderr = fopen("php://stderr", "w");
$tmp = tempnam(sys_get_temp_dir(), "io");
$f = fopen($tmp, "a");
fwrite($f, "x");
fflush($f);
fclose($f);
@unlink($tmp);
$h = fopen("php://temp", "w+");
fwrite($h, "a");
rewind($h);
echo stream_get_contents($h);
fclose($h);
?>
--EXPECT--
