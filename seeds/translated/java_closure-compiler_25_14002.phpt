--TEST--
Translated seed (inferredbugs-java: closure-compiler/25)
--FILE--
<?php
$x = 1;
echo $x . "\n";
$x = 2;
echo $x . "\n";
--EXPECT--
