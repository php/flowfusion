--TEST--
Translated seed (inferredbugs-java: aviator/11)
--FILE--
<?php

$largeNumber = PHP_INT_MAX;
$overflowedValue = $largeNumber + 1;

echo $overflowedValue;

$decimalValue = 1.5;
$result = $overflowedValue + $decimalValue;

echo $result;
--EXPECT--
