--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/8)
--FILE--
<?php

function triggerBug() {
    $a = PHP_INT_MAX; // Largest integer value in PHP
    $b = 1;

    $result = $a + $b; // Integer overflow simulation
    echo $result; // Should print a negative integer due to overflow
}

triggerBug();
--EXPECT--
