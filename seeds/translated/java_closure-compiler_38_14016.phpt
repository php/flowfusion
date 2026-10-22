--TEST--
Translated seed (inferredbugs-java: closure-compiler/38)
--FILE--
<?php

$x = 1;
if ($x) {
    return;
    echo 'unreachable';
}
echo 'reachable';
--EXPECT--
