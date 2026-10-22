--TEST--
Translated seed (inferredbugs-java: J2V8/13)
--FILE--
<?php
$numberArray = range(1, 100000);
$numberArray = array_map(function($n) {
    return $n * 2;
}, $numberArray);
$sum = array_reduce($numberArray, function($carry, $item) {
    return $carry + $item;
}, 0);
echo $sum;
--EXPECT--
