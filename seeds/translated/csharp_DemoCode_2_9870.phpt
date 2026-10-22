--TEST--
Translated seed (inferredbugs-csharp: DemoCode/2)
--FILE--
<?php

function main() {
    $a = 0;
    $b = 0;
    
    for ($i = 0; $i < 20; $i++) {
        if (mt_rand() / mt_getrandmax() >= 0.5) {
            $a++;
        } else {
            $b++;
        }
        echo "a={$a}, b={$b}\n";
    }
}

main();
--EXPECT--
