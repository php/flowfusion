--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/10)
--FILE--
<?php

function triggerBugPattern() {
    $array = [];
    
    for ($i = 0; $i < 300; $i++) {
        $array[] = $i; // Adding 300 elements to an array
    }
    
    // This will trigger a warning when trying to create an array with more than 255 elements
    // and will not behave as expected
    $bigArray = array_fill(0, 300, $array);
    
    // The following will attempt to access an out-of-bounds index
    // which is a kind of edge case that can be triggering errors
    echo $bigArray[300][0]; // Accessing an out of bounds index
}

triggerBugPattern();

?>
--EXPECT--
