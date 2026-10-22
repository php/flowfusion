--TEST--
Translated seed (inferredbugs-java: closure-compiler/15)
--FILE--
<?php

function inlineFunction($fn, $arg) {
    return $fn($arg);
}

function directInline($num) {
    return $num * $num;
}

$num = 5;
$result = inlineFunction('directInline', $num);
echo $result;
--EXPECT--
