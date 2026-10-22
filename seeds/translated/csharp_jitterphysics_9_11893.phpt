--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/9)
--FILE--
<?php
$xs = [0.0, 1.0, -1.0, 0.1, 2.0, 1e-9];
$acc = 0.0;
foreach ($xs as $x) {
    $d = pow(0.85, $x);
    $acc += $d;
    $y = ($x < 0.001) ? ($x * (0.5 * $x - ($x * $x * $x) * 0.020833333333 * $x * $x)) : (sin(0.5 * $x * $x) / ($x == 0.0 ? 1.0 : $x));
    $q = [$y, $y, $y, cos($x * 0.5)];
    $n = sqrt($q[0]*$q[0] + $q[1]*$q[1] + $q[2]*$q[2] + $q[3]*$q[3]);
    if ($n != 0.0) {
        foreach ($q as &$v) $v /= $n;
        unset($v);
    }
    $acc += $q[3];
}
$map = [];
for ($i = 0; $i < 4; $i++) {
    $a = "b" . $i;
    $b = "c" . (3 - $i);
    $k = $a < $b ? $a . "|" . $b : $b . "|" . $a;
    if (!isset($map[$k])) $map[$k] = [];
    $map[$k][] = $i;
}
$stack = [];
$queue = [];
foreach ($map as $k => $v) {
    $queue[] = $k;
    if (count($v) > 1) $stack[] = $v;
}
while ($queue) {
    $k = array_shift($queue);
    $acc += strlen($k);
}
while ($stack) {
    $v = array_pop($stack);
    $acc += array_sum($v);
}
echo $acc >= 0 ? "" : "";
?>
--EXPECT--
