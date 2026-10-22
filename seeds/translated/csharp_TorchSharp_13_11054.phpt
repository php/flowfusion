--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/13)
--FILE--
<?php
$cases = [
    [1],
    [2, 3],
    [],
];

foreach ($cases as $dims) {
    $shape = $dims;
    if (count($shape) === 0) {
        $shape = [0];
    }

    $total = 1;
    foreach ($dims as $d) {
        $total *= $d;
    }

    $data = array_fill(0, $total, 0);

    $clone = $data;
    $cloneEmpty = array_fill(0, $total, 0);

    if (count($shape) === 1) {
        $reshaped = $data;
    } elseif (count($shape) === 2) {
        $reshaped = [];
        $idx = 0;
        for ($i = 0; $i < $shape[0]; $i++) {
            $row = [];
            for ($j = 0; $j < $shape[1]; $j++) {
                $row[] = $data[$idx++];
            }
            $reshaped[] = $row;
        }
    } else {
        $reshaped = $data;
    }

    $pinIndex = 0;
    if ($pinIndex < 0 || $pinIndex > $total) {
        throw new RuntimeException("Index out of array bound.");
    }
}

echo "";
?>
--EXPECT--
