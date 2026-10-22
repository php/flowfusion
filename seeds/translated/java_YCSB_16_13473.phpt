--TEST--
Translated seed (inferredbugs-java: YCSB/16)
--FILE--
<?php
// Integer overflow example in PHP
$largeNumber = PHP_INT_MAX; // Get maximum integer value
$overflowedNumber = $largeNumber + 1; // This would trigger overflow in other languages

echo "Large Number: $largeNumber\n";
echo "Overflowed Number: $overflowedNumber\n"; // In PHP, it will not overflow, it will convert to float
?>
--EXPECT--
