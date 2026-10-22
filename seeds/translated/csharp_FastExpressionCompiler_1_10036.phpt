--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/1)
--FILE--
<?php

class Issue91_Issue95_Tests
{
    public function refAssign()
    {
        $exampleB = 5.0;
        $exampleB += 3.0;
        echo $exampleB; // Output: 8.0
    }

    public function nullComparisonTest()
    {
        $pParam = "aaa";
        $result = ($pParam !== null) ? 1 : 0;
        echo $result; // Output: 1
    }

    public function testAddAssign()
    {
        $exampleB = 5.0;
        $exampleB += 3.0;
        echo $exampleB; // Output: 8.0
    }
}

$tests = new Issue91_Issue95_Tests();
$tests->refAssign();
$tests->nullComparisonTest();
$tests->testAddAssign();
--EXPECT--
