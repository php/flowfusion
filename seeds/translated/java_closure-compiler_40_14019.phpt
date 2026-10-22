--TEST--
Translated seed (inferredbugs-java: closure-compiler/40)
--FILE--
<?php

$left = "10";
$right = 5;

$result = $left + $right;

echo $result;
--EXPECT--
