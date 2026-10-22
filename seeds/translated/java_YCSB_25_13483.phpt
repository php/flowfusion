--TEST--
Translated seed (inferredbugs-java: YCSB/25)
--FILE--
<?php
$maxInt = PHP_INT_MAX;
$value = $maxInt + 1;
echo $value;
?>
--EXPECT--
