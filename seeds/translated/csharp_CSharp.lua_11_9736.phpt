--TEST--
Translated seed (inferredbugs-csharp: CSharp.lua/11)
--FILE--
<?php

function createArrayWithNulls($length) {
    return array_fill(0, $length, null);
}

function testArrayFill() {
    $array = createArrayWithNulls(5);
    foreach ($array as $value) {
        if (is_null($value)) {
            // Intentionally left blank to illustrate the behavior
        }
    }
}

function main() {
    testArrayFill();
}

main();
--EXPECT--
