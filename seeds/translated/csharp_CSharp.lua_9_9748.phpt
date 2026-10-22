--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/9)
--FILE--
<?php

function triggerBug() {
    $a = 1.0;
    $b = 0.0;

    // Edge case: division by zero in PHP
    if ($b) {
        $c = $a / $b;
    } else {
        $c = "undefined"; // Handling division by zero
    }

    return $c;
}

$cause = triggerBug();
echo $cause;
--EXPECT--
