--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/2)
--FILE--
<?php

function bugTriggeringTest() {
    $a = 1.0; // Float initialization
    $b = 0.0000001; // Small float
    $c = $a + $b; // Addition that may result in precision issues

    // Output the result
    echo "Result: " . $c . PHP_EOL;

    $array = array_fill(0, 1000000, 1); // Large array initialization
    $sum = array_sum($array); // Sum of the array

    // Output the sum
    echo "Sum of large array: " . $sum . PHP_EOL;

    // Integer overflow example
    $intOverflow = 2147483647; // Max int value in typical 32-bit PHP
    $intOverflow++; // This should overflow and become -2147483648

    // Output the result of the overflow
    echo "Integer overflow result: " . $intOverflow . PHP_EOL;
}

bugTriggeringTest();
--EXPECT--
