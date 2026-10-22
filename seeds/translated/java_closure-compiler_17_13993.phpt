--TEST--
Translated seed (inferredbugs-java: closure-compiler/17)
--FILE--
<?php
$needsDefaultResult = true;
$isCallInLoop = false;

function mutate($fnName, $fnBody, &$resultName) {
    $hasReturnAtExit = false;
    $returnCount = substr_count($fnBody, 'return');
    if ($returnCount > 0) {
        $hasReturnAtExit = substr($fnBody, -6, 6) === 'return';
        if ($hasReturnAtExit) {
            $fnBody = preg_replace('/return (.+);?/', "$resultName = $1;", $fnBody);
            $returnCount--;
        }

        if ($returnCount > 0) {
            $fnBody = preg_replace('/return/', '', $fnBody);
            $fnBody = rtrim($fnBody) . "\nbreak;";
        }
    }

    if ($needsDefaultResult && !$hasReturnAtExit) {
        $fnBody .= "\n$resultName = null;"; // Using null as a dummy value
    }

    return $fnBody;
}

$fnName = 'someFunction';
$resultName = 'result';
$fnBody = 'return 42;';

$injectedBody = mutate($fnName, $fnBody, $resultName);
eval($injectedBody);
echo $resultName;
--EXPECT--
