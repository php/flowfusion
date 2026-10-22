--TEST--
Translated seed (inferredbugs-csharp: PerformanceMonitor/6)
--FILE--
<?php
$monitor = fopen('php://memory', 'r+');
flock($monitor, LOCK_EX);
fwrite($monitor, "App");
rewind($monitor);
fread($monitor, 3);
for ($i = 0; $i < 1000; $i++) {
    $a = [];
    for ($j = 0; $j < 100; $j++) {
        $a[] = str_repeat("x", 1024);
    }
    unset($a);
}
$fp = fopen('php://memory', 'r+');
for ($i = 0; $i < 1000; $i++) {
    fwrite($fp, "data");
    rewind($fp);
    fread($fp, 4);
}
flock($monitor, LOCK_UN);
fclose($fp);
fclose($monitor);
--EXPECT--
