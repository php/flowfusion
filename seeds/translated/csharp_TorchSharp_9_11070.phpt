--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/9)
--FILE--
<?php
$cases = [
    [1],
    [2, 3],
    [],
    [0],
    [1, 1, 1],
];

foreach ($cases as $sizes) {
    $shape = $sizes;
    if (count($sizes) === 0) {
        $shape = [0];
    }

    $total = 1;
    foreach ($sizes as $s) {
        $total *= $s;
    }

    $data = array_fill(0, $total, 0);

    if (count($shape) === 1) {
        $reshaped = $data;
    } elseif (count($shape) === 2) {
        $reshaped = [];
        for ($i = 0, $k = 0; $i < $shape[0]; $i++) {
            $row = [];
            for ($j = 0; $j < $shape[1]; $j++, $k++) {
                $row[] = $data[$k];
            }
            $reshaped[] = $row;
        }
    } else {
        $reshaped = $data;
    }

    $clone = array_fill(0, count($data), 0);
    $copy = $data;
    $copy[0] = $copy[0] ?? 0;
    $cloneEmpty = array_fill(0, count($data), 0);

    $sum = 0;
    foreach ($cloneEmpty as $v) {
        $sum += $v;
    }
}

echo "";
?>
--EXPECT--
