--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/1)
--FILE--
<?php

function buggyFunction($array) {
    $count = count($array);
    for ($i = 0; $i <= $count; $i++) {
        echo $array[$i]; // Intentional out of bounds access
    }
}

$array = [1, 2, 3, 4, 5];
buggyFunction($array);
--EXPECT--
