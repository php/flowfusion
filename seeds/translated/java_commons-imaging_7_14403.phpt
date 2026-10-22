--TEST--
Translated seed (inferredbugs-java: commons-imaging/7)
--FILE--
<?php

function nextCombination(&$sequence, $max) {
    for ($i = 0; $i < count($sequence); $i++) {
        if ($sequence[$i] == $max) {
            $sequence[$i] = 0;
        } else {
            $sequence[$i]++;
            return true;
        }
    }
    return false;
}

$uncompressed = str_repeat("\0", 2);
$combinations = array_fill(0, 10, 0);
do {
    for ($x = 0; $x < 5; $x++) {
        if ($combinations[$x] != 0) {
            $uncompressed[0] = chr(ord($uncompressed[0]) | (0x80 >> $x));
        }
    }
    for ($x = 0; $x < 5; $x++) {
        if ($combinations[5 + $x] != 0) {
            $uncompressed[1] = chr(ord($uncompressed[1]) | (0x80 >> $x));
        }
    }

    // Simulate compression and decompression (dummy)
    $compressed = $uncompressed; // Placeholder for actual compression
    $result = $uncompressed; // Placeholder for actual decompression

} while (nextCombination($combinations, 1));

$combinations = array_fill(0, 10, 0);
$image = array_fill(0, 2, array_fill(0, 5, 0xFFFFFF));
do {
    for ($x = 0; $x < 5; $x++) {
        $image[0][$x] = ($combinations[$x] == 0) ? 0xFFFFFF : 0;
    }
    for ($x = 0; $x < 5; $x++) {
        $image[1][$x] = ($combinations[5 + $x] == 0) ? 0xFFFFFF : 0;
    }

    // Simulate image write and read (dummy)
    $params = ["compression" => "CCITT_1D"];
    $compressed = $image; // Placeholder for actual compression
    $result = $image; // Placeholder for actual read

} while (nextCombination($combinations, 1));
--EXPECT--
