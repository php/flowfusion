--TEST--
Translated seed (inferredbugs-java: data-algorithms-book/1)
--FILE--
<?php

$input = [
    "x,2,9",
    "y,2,5",
    "x,1,3",
    "y,1,7",
    "y,3,1",
    "x,3,6",
    "z,1,4",
    "z,2,8",
    "z,3,7",
    "z,4,0",
];

$pairs = [];

foreach ($input as $line) {
    list($name, $time, $value) = explode(',', $line);
    $pairs[$name][] = [(int)$time, (int)$value];
}

foreach ($pairs as $name => &$values) {
    usort($values, function($a, $b) {
        return $a[0] <=> $b[0];
    });
}

foreach ($pairs as $name => $values) {
    echo $name . " => [";
    foreach ($values as $value) {
        echo "(" . $value[0] . "," . $value[1] . "), ";
    }
    echo "\b\b] \n";
}
--EXPECT--
