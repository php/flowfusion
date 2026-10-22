--TEST--
Translated seed (inferredbugs-java: closure-compiler/16)
--FILE--
<?php

function testInline() {
    $x = 1;
    $result = inlineFunction($x);
    echo $result;
}

function inlineFunction($arg) {
    return $arg + $arg;
}

testInline();
--EXPECT--
