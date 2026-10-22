--TEST--
Translated seed (inferredbugs-java: aviator/10)
--FILE--
<?php

$maxInt = PHP_INT_MAX;
$overflow = $maxInt + 1;

if ($overflow === PHP_INT_MIN) {
    echo "Integer overflow occurred!";
} else {
    echo "No overflow, value: " . $overflow;
}
--EXPECT--
