--TEST--
Translated seed (inferredbugs-csharp: ILRuntime/1)
--FILE--
<?php
$h = fopen('php://stdout', 'w');
fwrite($h, "");
fclose($h);
?>
--EXPECT--
