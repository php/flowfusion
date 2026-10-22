--TEST--
Translated seed (inferredbugs-java: cloud-slang/31)
--FILE--
<?php

$asyncLoopExpressionErrorMessage = "Error evaluating async loop expression in step";

$asyncLoopStatement = ["1", "2", "3"]; // Simulating an async loop statement
$nodeName = "TestNode";

function evalExpr($expression) {
    return $expression; // Simulate evaluation by returning the expression directly
}

function generateAsyncLoopExpressionMessage($nodeName, $message) {
    global $asyncLoopExpressionErrorMessage;
    return $asyncLoopExpressionErrorMessage . " '" . $nodeName . "', error is: \n" . $message;
}

$result = [];
try {
    $evalResult = evalExpr($asyncLoopStatement);
    if ($evalResult !== null) {
        foreach ($evalResult as $serializable) {
            if (is_string($serializable)) {
                $result[] = $serializable; // Simulating Value creation
            }
        }
    }
} catch (Throwable $t) {
    throw new RuntimeException(generateAsyncLoopExpressionMessage($nodeName, $t->getMessage()), $t);
}

if (empty($result)) {
    throw new RuntimeException(generateAsyncLoopExpressionMessage($nodeName, "expression is empty"));
}
--EXPECT--
