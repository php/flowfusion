--TEST--
Translated seed (inferredbugs-java: YCSB/21)
--FILE--
<?php

$keys = range(0, 100);
$values = array_map(function($key) { return str_repeat('A', 100); }, $keys);
$length = count($keys);
$maxSize = 1000;

for ($i = 0; $i < $length; $i++) {
    if (strlen($values[$i]) > $maxSize) {
        echo "Value exceeds maximum size\n";
        break;
    }
}
--EXPECT--
