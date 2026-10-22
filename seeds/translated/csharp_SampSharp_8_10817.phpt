--TEST--
Translated seed (inferredbugs-csharp: SampSharp/8)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
$old = fopen('php://stdout', 'w');
fwrite($h, "x");
fflush($h);
fclose($h);
?>
--EXPECT--
