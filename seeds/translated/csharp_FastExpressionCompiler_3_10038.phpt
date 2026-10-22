--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/3)
--FILE--
<?php

function triggerBugPattern() {
    $maxInt = PHP_INT_MAX;
    $overflowedValue = $maxInt + 1; // This will cause an integer overflow
    echo "Overflowed value: " . $overflowedValue . PHP_EOL; // Outputs the overflowed value
}

triggerBugPattern();

?>
--EXPECT--
