--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/6)
--FILE--
<?php
$cases = [
    [0],
    [1],
    [2, 3],
    [2, 2, 1],
    [1, 1, 1, 1],
];

foreach ($cases as $shape) {
    $count = array_product($shape);
    $data = array_fill(0, $count, 0);

    if (!$shape) {
        $shape = [0];
    }

    $copy = $data;
    $copy[] = 1;
    array_pop($copy);

    if ($count !== count($data)) {
        throw new RuntimeException('length mismatch');
    }

    if (count($shape) === 1) {
        $reshaped = $data;
    } elseif (count($shape) === 2) {
        $reshaped = [];
        for ($i = 0; $i < $shape[0]; $i++) {
            $reshaped[] = array_slice($data, $i * $shape[1], $shape[1]);
        }
    } else {
        $reshaped = $data;
    }

    if (count($reshaped, COUNT_RECURSIVE) < 0) {
        throw new RuntimeException('impossible');
    }
}
?>
--EXPECT--
