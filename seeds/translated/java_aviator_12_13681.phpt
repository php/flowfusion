--TEST--
Translated seed (inferredbugs-java: aviator/12)
--FILE--
<?php

$maxInt = PHP_INT_MAX;
$overflow = $maxInt + 1;

if ($overflow === PHP_INT_MIN) {
    echo "Integer overflow occurred.\n";
} else {
    echo "No overflow: $overflow\n";
}

$largeNumber = 1e20; // A number larger than PHP_INT_MAX
$result = $largeNumber . 1; // Implicit type coercion to string

echo "Result of large number and integer concatenation: $result\n";

$array = [];
for ($i = 0; $i < 5; $i++) {
    $array[$i] = $i;
}
$result = $array[5] ?? 'Undefined index'; // Testing undefined index behavior
echo "Attempt to access undefined index: $result\n";

$a = "123abc";
$b = $a + 1; // Type coercion from string to integer
echo "Result of adding string to integer: $b\n"; 

$c = "100" . "200"; // String concatenation
echo "Result of string concatenation: $c\n";

$d = 0;
try {
    $e = 1 / $d; // Division by zero
} catch (Throwable $t) {
    echo "Caught exception: " . $t->getMessage() . "\n";
}
?>
--EXPECT--
