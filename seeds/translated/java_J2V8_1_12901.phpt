--TEST--
Translated seed (inferredbugs-java: J2V8/1)
--FILE--
<?php

$runtimeCounter = 0;
$debugEnabled = false;

function createV8Runtime() {
    global $runtimeCounter;
    $runtimeCounter++;
    return true;
}

function releaseRuntime() {
    global $runtimeCounter;
    if ($runtimeCounter > 0) {
        $runtimeCounter--;
    }
}

function executeIntegerScript($script) {
    return eval("return $script;");
}

function executeDoubleScript($script) {
    return eval("return $script;");
}

function executeStringScript($script) {
    return eval("return \"$script\";");
}

function executeBooleanScript($script) {
    return eval("return $script;");
}

function executeArrayScript($script) {
    return eval("return [$script];");
}

function terminateExecution() {
    // Termination logic if needed
}

createV8Runtime();
$result1 = executeIntegerScript('1 + 1');
$result2 = executeDoubleScript('1.5 + 2.5');
$result3 = executeStringScript('"Hello" . " World"');
$result4 = executeBooleanScript('true');
$result5 = executeArrayScript('1, 2, 3');

releaseRuntime();
--EXPECT--
