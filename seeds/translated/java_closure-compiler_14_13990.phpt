--TEST--
Translated seed (inferredbugs-java: closure-compiler/14)
--FILE--
<?php

$x = 1;
echo $x . "\n";
$x = 2;
echo $x . "\n";

$y = 3;
echo $y . "\n";
$y = 4;
echo $y . "\n";
--EXPECT--
