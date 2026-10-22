--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/10)
--FILE--
<?php
$cases = [
    [0],
    [2, 3],
    [1, 4, 1],
    [],
];

foreach ($cases as $sizes) {
    $shape = $sizes;
    if (!$shape) {
        $shape = [0];
    }

    $len = 1;
    foreach ($sizes as $d) {
        $len *= $d;
    }

    $buf = array_fill(0, $len, 0);

    if ($len > 0) {
        for ($i = 0; $i < $len; $i++) {
            $buf[$i] = $i & 255;
        }
    }

    $clone = $buf;
    $empty = array_fill(0, $len, 0);

    if (count($shape) === 1) {
        $reshaped = $buf;
    } elseif (count($shape) === 2) {
        $reshaped = array_chunk($buf, $shape[1]);
    } else {
        $reshaped = $buf;
    }

    if ($len > 0) {
        $pin = &$buf[0];
        $pin = ($pin + 1) & 255;
        unset($pin);
    }

    $clone2 = $clone;
    $empty2 = $empty;
}

$big = PHP_INT_MAX > 2147483647 ? 2147483647 : PHP_INT_MAX;
$tmp = str_repeat("\0", 1);
unset($tmp);
echo "";
?>
--EXPECT--
