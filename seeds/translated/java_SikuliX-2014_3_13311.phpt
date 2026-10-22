--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/3)
--FILE--
<?php

$max = 255;
$value = 256;

if ($value > $max) {
    $result = $value - $max;
    echo $result; // This will demonstrate an integer overflow-like behavior in PHP
}
--EXPECT--
