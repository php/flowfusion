--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/5)
--FILE--
<?php

function triggerBug() {
    $x = 2147483647; // Maximum value for a signed 32-bit integer
    $y = 1;
    $result = $x + $y; // This will overflow in languages with fixed integer sizes
    return $result;
}

function main() {
    $result = triggerBug();
    echo "Result: " . $result . "\n"; // Expecting an overflow scenario
}

main();
--EXPECT--
