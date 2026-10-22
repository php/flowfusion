--TEST--
Translated seed (inferredbugs-java: closure-compiler/47)
--FILE--
<?php

$warnings = [];

function analyze($x) {
    if ($x < 0) {
        global $warnings;
        $warnings[] = "Warning: Negative value detected: $x";
    }
    return $x * 2;
}

function main() {
    for ($i = -10; $i < 10; $i++) {
        analyze($i);
    }
    global $warnings;
    foreach ($warnings as $warning) {
        echo $warning . "\n";
    }
}

main();
--EXPECT--
