--TEST--
Translated seed (inferredbugs-csharp: NetworkComms.Net/13)
--FILE--
<?php
$log = __DIR__ . '/file.txt';
$h = fopen($log, 'ab');
if ($h) {
    fwrite($h, date('H:i:s') . " main message\n");
    fclose($h);
}
echo "done\n";
?>
--EXPECT--
