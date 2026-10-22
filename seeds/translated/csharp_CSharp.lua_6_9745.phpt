--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/6)
--FILE--
<?php

function testBugPattern() {
    $array = [];
    for ($i = 0; $i < 10; $i++) {
        $array[$i] = $i;
    }
    
    // Edge case: Unset the last index
    unset($array[9]);

    // This should trigger the bug pattern related to accessing an out-of-bounds index
    $value = $array[9]; // Trying to access an index that no longer exists
    return $value; // Should not return any value as index 9 does not exist
}

testBugPattern();
--EXPECT--
