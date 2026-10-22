--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/2)
--FILE--
<?php

function refAssign(&$a1) {
    $a1 += 3.0;
}

function nullComparisonTest($p) {
    return $p !== null ? 1 : 0;
}

function testAddAssign(&$a1) {
    $a1 += 3.0;
}

$exampleB = 5.0;
refAssign($exampleB);
echo $exampleB, "\n"; // Should output 8.0

echo nullComparisonTest("aaa"), "\n"; // Should output 1
echo nullComparisonTest(null), "\n"; // Should output 0

$exampleC = 5.0;
testAddAssign($exampleC);
echo $exampleC, "\n"; // Should output 8.0

?>
--EXPECT--
