--TEST--
Translated seed (inferredbugs-java: J2V8/3)
--FILE--
<?php

function createV8Runtime() {
    $script = 'function add(a, b) { return a + b; } add(2, 3);';
    eval($script);
}

createV8Runtime();
--EXPECT--
