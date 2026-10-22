--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/7)
--FILE--
<?php

function createLargeArray() {
    // Creating a large array that might cause memory issues
    $largeArray = [];
    for ($i = 0; $i < 1000000; $i++) {
        $largeArray[$i] = $i;
    }
    return $largeArray;
}

function performOperationOnLargeArray($array) {
    // Trying to access an element out of bounds to test an edge case
    $outOfBoundsAccess = $array[1000000]; // This should trigger an undefined index notice
    return $outOfBoundsAccess;
}

function main() {
    $largeArray = createLargeArray();
    performOperationOnLargeArray($largeArray);
}

main();
--EXPECT--
