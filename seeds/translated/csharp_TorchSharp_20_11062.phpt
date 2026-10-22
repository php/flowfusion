--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/20)
--FILE--
<?php
$x = array_fill(0, 10000, 1);
$y = $x;
$y[0] -= 1;
$z = array_map(fn($v) => $v * 0.5, $x);
$sum = 0;
for ($i = 0; $i < 10000; $i++) {
    $sum += $z[$i];
}
$loss = 0.0;
for ($i = 0; $i < 10; $i++) {
    $prev = $loss;
    $loss = 0.0;
    for ($j = 0; $j < 1000; $j++) {
        $d = ($x[$j] - $y[$j % 10000]);
        $loss += $d * $d;
    }
    $x[0] = (int)$x[0] - 1;
}
echo $sum + $loss;
?>
--EXPECT--
