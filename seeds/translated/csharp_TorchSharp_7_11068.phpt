--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/7)
--FILE--
<?php
$cases = [
    [1],
    [2, 3],
    [],
    [4, 1],
];

foreach ($cases as $sizes) {
    $shape = $sizes;
    if (!$shape) {
        $shape = [0];
    }

    $len = array_product($sizes);
    if ($len === false) {
        $len = 0;
    }

    $data = array_fill(0, $len, 0);
    $clone = $data;
    $reshaped = $data;

    if (count($shape) === 1) {
        $reshaped = $data;
    } elseif (count($shape) === 2) {
        $reshaped = array_chunk($data, $shape[1]);
    }

    $emptyClone = array_fill(0, $len, 0);
    $copy = $data;
    $copy[0] = 1;
    $clone[0] = $copy[0];
    $data = $clone;
}

echo "";
?>
--EXPECT--
