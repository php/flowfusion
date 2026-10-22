--TEST--
Translated seed (inferredbugs-java: closure-compiler/43)
--FILE--
<?php

function typeCoercionTest() {
    $x = "10"; // string
    $y = 5;    // integer
    $result = $x + $y; // coercion occurs here
    echo $result; // expected output is 15
}

typeCoercionTest();
--EXPECT--
