--TEST--
Translated seed (inferredbugs-csharp: azure-functions-durable-extension/3)
--FILE--
<?php
$provider = new stdClass();
$provider->MaximumDelayTime = 60;
$provider->LongRunningTimerIntervalLength = 25;
echo '';
?>
--EXPECT--
