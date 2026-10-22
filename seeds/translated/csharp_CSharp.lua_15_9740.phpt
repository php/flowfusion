--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/15)
--FILE--
<?php

const K_STRING_CONST_INLINE_COUNT = 27;

function testBugPattern() {
    $values = [1, 2, 3];
    $sum = 0;

    foreach ($values as $value) {
        $sum += $value;
    }

    // Simulate a bug pattern akin to an overflow or incorrect type handling
    $largeNumber = PHP_INT_MAX;
    $sum += $largeNumber;

    // Display the sum
    echo $sum;
}

testBugPattern();
--EXPECT--
