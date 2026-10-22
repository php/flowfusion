--TEST--
Translated seed (inferredbugs-java: closure-compiler/23)
--FILE--
<?php
function bugPattern() {
    $a = 5;
    $b = 10;
    $c = "5";
    
    // Type coercion leading to unexpected results
    $result1 = $a + $c; // 5 (int) + "5" (string) -> 10 (int)
    $result2 = $a . $c; // 5 (int) . "5" (string) -> "55" (string)

    // Array operations on null
    $array = null;
    $array[] = "test"; // Should raise a warning

    // Division by zero
    $division = 1 / 0; // Should raise a warning

    // Large number leading to float precision issue
    $largeNumber = 1e20 + 1; // May not represent the exact value due to float precision
}

bugPattern();
--EXPECT--
