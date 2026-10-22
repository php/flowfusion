--TEST--
Translated seed (inferredbugs-java: closure-compiler/39)
--FILE--
<?php

function testUnreachableCodeElimination() {
    $x = 10;

    if ($x > 5) {
        return; // Code below this line is unreachable
        echo "This will never execute.";
    }

    $y = $x + 5; // This line is also dead code
}

testUnreachableCodeElimination();
--EXPECT--
