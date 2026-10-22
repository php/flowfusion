--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/4)
--FILE--
<?php

function testIntegerOverflow() {
    $maxInt = PHP_INT_MAX;
    $result = $maxInt + 1; // This should cause an overflow in C#

    echo "Result of adding 1 to max int (PHP_INT_MAX): " . $result . "\n";
}

function testFloatPrecision() {
    $a = 0.1;
    $b = 0.2;
    $result = $a + $b; // Expected to be 0.3, but may not be due to floating point precision issues

    echo "Result of 0.1 + 0.2: " . $result . "\n";
}

function testArrayOverflow() {
    $array = [];
    for ($i = 0; $i <= PHP_INT_MAX; $i++) {
        $array[] = $i; // Attempt to add elements until overflow
    }

    echo "Length of array: " . count($array) . "\n";
}

function main() {
    testIntegerOverflow();
    testFloatPrecision();
    // Uncommenting the next line may cause PHP to run out of memory or hit a resource limit
    // testArrayOverflow(); 
}

main();
--EXPECT--
