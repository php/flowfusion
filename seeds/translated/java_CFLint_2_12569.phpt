--TEST--
Translated seed (inferredbugs-java: CFLint/2)
--FILE--
<?php

$REPEAT_THRESHOLD = 3;
$threshold = $REPEAT_THRESHOLD;
$literals = [];
$done = [];

function isCommon($name) {
    return $name === "1" || $name === "0" || $name === "" || $name === "true" || $name === "false";
}

function magicValue($name, $lineNo) {
    echo "Literal {$name} occurs several times. Consider giving it a name and not hard coding values at line {$lineNo}.\n";
}

function expression($literal, $context) {
    global $threshold, $literals, $done;

    $name = str_replace("'", "", $literal);
    $count = 1;

    if (isCommon($name)) {
        return;
    }

    if (!isset($literals[$name])) {
        $literals[$name] = $count;
        $done[$name] = false;
    } else {
        $count = $literals[$name];
        $count++;
        $literals[$name] = $count;
    }

    if ($count > $threshold && !$done[$name]) {
        $lineNo = $context['startLine'] - 1; // Simulate getting the line number
        magicValue($name, $lineNo);
        $done[$name] = true;
    }
}

// Simulate running the expression
$context = ['startLine' => 5];
$testLiterals = ["'value1'", "'value1'", "'value1'", "'value2'", "'value2'"];

foreach ($testLiterals as $literal) {
    expression($literal, $context);
}
--EXPECT--
