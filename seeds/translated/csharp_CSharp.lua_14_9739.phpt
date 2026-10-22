--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/14)
--FILE--
<?php

function testBugPattern() {
    $a = 2147483647; // maximum value for a 32-bit signed integer
    $b = $a + 1; // integer overflow
    echo $b; // should wrap around to -2147483648
}

testBugPattern();
--EXPECT--
