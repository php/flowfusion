--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/3)
--FILE--
<?php

function testBugPattern() {
    $array = [];
    for ($i = 0; $i < 10; $i++) {
        $array[] = $i;
    }
    
    // Intentionally create an out-of-bounds condition
    $index = 10; // This index is out of bounds
    $value = $array[$index] ?? null; // Using null coalescing to avoid Notice
    
    echo "Value at index $index: " . ($value !== null ? $value : "Index out of bounds") . PHP_EOL;
}

testBugPattern();
--EXPECT--
