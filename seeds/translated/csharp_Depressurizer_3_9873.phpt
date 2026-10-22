--TEST--
Translated seed (inferredbugs-csharp: Depressurizer/3)
--FILE--
<?php

function main() {
    $array = [];
    for ($i = 0; $i < 1000000; $i++) {
        $array[] = str_repeat("A", 100); // Simulating memory allocation
    }
    $array2 = $array; // Memory aliasing
    foreach ($array2 as $value) {
        // Altering the second array to see effects on the first
        $value .= "B";
    }
    // Exiting without error
}

main();

?>
--EXPECT--
