--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/7)
--FILE--
<?php
$xs = [0.0, 1.0, -1.0, 0.1, 0.85, 2.0, 1e-6, 1e6];
$acc = 0.0;
foreach ($xs as $t) {
    if ($t == 0.0) continue;
    if ($t < 0.0) continue;
    $a = pow(0.85, $t);
    $b = pow(0.85, $t);
    $acc += $a + $b;
    $v = [0.0, -9.81, 0.0];
    $lin = [1.0, 2.0, 3.0];
    foreach ($v as $i => $g) $lin[$i] += $g * $t;
    $ang = [0.001 * $t, 0.002 * $t, 0.003 * $t];
    $angle = sqrt($ang[0] * $ang[0] + $ang[1] * $ang[1] + $ang[2] * $ang[2]);
    if ($angle < 0.001) {
        $axis = [$ang[0] * (0.5 * $t - ($t * $t * $t) * 0.020833333333 * $angle * $angle), $ang[1] * (0.5 * $t - ($t * $t * $t) * 0.020833333333 * $angle * $angle), $ang[2] * (0.5 * $t - ($t * $t * $t) * 0.020833333333 * $angle * $angle)];
    } else {
        $s = sin(0.5 * $angle * $t) / $angle;
        $axis = [$ang[0] * $s, $ang[1] * $s, $ang[2] * $s];
    }
    $q = [$axis[0], $axis[1], $axis[2], cos($angle * $t * 0.5)];
    $n = sqrt($q[0] * $q[0] + $q[1] * $q[1] + $q[2] * $q[2] + $q[3] * $q[3]);
    if ($n != 0.0) foreach ($q as $i => $x) $q[$i] = $x / $n;
    $acc += $lin[0] + $lin[1] + $lin[2] + $q[3];
}
echo $acc, PHP_EOL;
?>
--EXPECT--
