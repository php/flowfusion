--TEST--
Translated seed (inferredbugs-java: closure-compiler/46)
--FILE--
<?php
function test() {
    $a = 1;
    $b = "2";
    $result = $a + $b; // Type coercion: integer and string
    $a = null;
    $result2 = $a + $b; // Type coercion: null and string
    $result3 = $result . $result2; // Concatenation of numeric and string results
    echo $result3; // Output the results
}

test();
--EXPECT--
