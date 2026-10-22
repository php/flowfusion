--TEST--
Translated seed (inferredbugs-java: commons-imaging/29)
--FILE--
<?php

function decompress($bytes, $expected) {
    $total = 0;
    $output = [];

    $i = 0;
    while ($total < $expected) {
        if ($i >= count($bytes)) {
            throw new Exception("Tiff: Unpack bits source exhausted: " . $i . ", done + " . $total . ", expected + " . $expected);
        }

        $n = $bytes[$i++];
        if ($n >= 0 && $n <= 127) {
            $count = $n + 1;
            $total += $count;
            for ($j = 0; $j < $count; $j++) {
                $output[] = $bytes[$i++];
            }
        } elseif ($n >= -127 && $n <= -1) {
            $b = $bytes[$i++];
            $count = -$n + 1;
            $total += $count;
            for ($j = 0; $j < $count; $j++) {
                $output[] = $b;
            }
        } elseif ($n == -128) {
            throw new Exception("Packbits: " . $n);
        }
    }

    return $output;
}

$bytes = [5, 1, 2, 3, 4, 5, -1, 1]; // Example input
$expected = 8; // Expected output length
$result = decompress($bytes, $expected);
print_r($result);
--EXPECT--
