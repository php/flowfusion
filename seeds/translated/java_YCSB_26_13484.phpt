--TEST--
Translated seed (inferredbugs-java: YCSB/26)
--FILE--
<?php
$maxInt = PHP_INT_MAX;
$overflow = $maxInt + 1;
$underflow = -($maxInt + 1);
echo "Overflow result: $overflow\n";
echo "Underflow result: $underflow\n";
?>
--EXPECT--
