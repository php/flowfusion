--TEST--
Translated seed (inferredbugs-java: asterisk-java/8)
--FILE--
<?php

$largeNumber = 2147483647;
$overflowed = $largeNumber + 1;
$boundaryCondition = $overflowed - $largeNumber;

echo $boundaryCondition;

$negativeBoundary = $boundaryCondition - 1;
echo $negativeBoundary;

$floatValue = 1.23456789;
$precisionTest = $floatValue * 1000000000;

echo $precisionTest;

$array = [];
for ($i = 0; $i < 1000; $i++) {
    $array[$i] = str_repeat("A", 1024);
}
$memoryUsage = memory_get_usage();
echo $memoryUsage;

$chainedOperations = $boundaryCondition * $negativeBoundary + $precisionTest;
echo $chainedOperations;

echo "Done";
--EXPECT--
