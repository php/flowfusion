--TEST--
Translated seed (inferredbugs-csharp: iotedge/1)
--FILE--
<?php
$logger = fopen('php://stdout', 'w');
$ts = date('Y-m-d H:i:s');
fwrite($logger, $ts . ' [INF] - ' . 'Logger initialized' . PHP_EOL);
fclose($logger);
?>
--EXPECT--
