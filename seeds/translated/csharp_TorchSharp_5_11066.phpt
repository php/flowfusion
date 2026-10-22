--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/5)
--FILE--
<?php
$cases = [
    [0],
    [2, 3],
    [1, 1, 1, 1],
    []
];

foreach ($cases as $sizes) {
    $shape = $sizes ?: [0];
    $total = 1;
    foreach ($sizes as $s) {
        $total *= $s;
    }
    $buffer = array_fill(0, $total, 0);

    $clone = $buffer;
    $clone[0] = 7;

    if ($total > 0) {
        $reshaped = $buffer;
        if (count($shape) === 1) {
            $reshaped = array_chunk($buffer, $shape[0]);
        } elseif (count($shape) === 2) {
            $reshaped = [];
            $idx = 0;
            for ($i = 0; $i < $shape[0]; $i++) {
                $reshaped[] = array_slice($buffer, $idx, $shape[1]);
                $idx += $shape[1];
            }
        }
        unset($reshaped);
    }
}

$big = array_fill(0, 4, 0);
$big[3] = 1;
$copy = $big;
$copy[1] = 9;
echo "";
?>
--EXPECT--
