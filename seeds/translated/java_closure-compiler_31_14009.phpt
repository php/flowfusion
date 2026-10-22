--TEST--
Translated seed (inferredbugs-java: closure-compiler/31)
--FILE--
<?php

function test() {
    $max = 10;
    $arr = [];
    for ($i = 0; $i < $max; $i++) {
        $arr[$i] = $i;
    }
    
    foreach ($arr as $key => $value) {
        if ($key < 5) {
            $value = $value * 2; // Modify the value but do not change the array
        }
    }
    
    for ($j = 0; $j < $max; $j++) {
        if ($j < 5) {
            echo $arr[$j] . " "; // This will print modified values incorrectly
        }
    }
}

test();
--EXPECT--
