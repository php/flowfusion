--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/4)
--FILE--
<?php

function testIntegerOverflow() {
    $maxInt = PHP_INT_MAX;
    $overflowedValue = $maxInt + 1; // This should trigger an overflow behavior in some contexts
    echo $overflowedValue; // Output the overflowed value
}

function testStringToIntConversion() {
    $strNumber = "123abc"; // This string cannot be converted to an integer entirely
    $convertedValue = (int)$strNumber; // Will truncate to 123
    echo $convertedValue; // Output the converted value
}

function testArrayPush() {
    $arr = [];
    array_push($arr, 1);
    array_push($arr, 2);
    array_push($arr, 3);
    echo implode(", ", $arr); // Output the array contents
}

function main() {
    testIntegerOverflow();
    echo "\n";
    testStringToIntConversion();
    echo "\n";
    testArrayPush();
}

main();
--EXPECT--
