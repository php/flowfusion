--TEST--
Translated seed (inferredbugs-java: closure-compiler/44)
--FILE--
<?php
$maxInt = PHP_INT_MAX;
$overflowed = $maxInt + 1;
echo $overflowed;
--EXPECT--
