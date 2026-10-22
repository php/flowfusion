--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/5)
--FILE--
<?php
$vals = [0, 1, -1, PHP_INT_MAX, PHP_INT_MIN, 0.0, -0.0, INF, -INF, NAN, "0", "1", "-1", "1e3", "abc", [], [1], true, false, null];
$events = [];
foreach ($vals as $a) {
    foreach ($vals as $b) {
        $events[] = [$a, $b];
    }
}
$state = [];
foreach ($events as $e) {
    $a = $e[0];
    $b = $e[1];
    $k = is_array($a) ? 'array' : (is_object($a) ? 'object' : gettype($a)) . ':' . @strval($a) . '|' . (is_array($b) ? 'array' : (is_object($b) ? 'object' : gettype($b)) . ':' . @strval($b));
    if (!isset($state[$k])) $state[$k] = 0;
    $state[$k] += 1;
    $x = $a + $b;
    $y = $a * $b;
    $z = $a == $b;
    $w = $a <=> $b;
    if (is_float($x) && is_nan($x)) $state[$k] ^= 1;
    if (is_float($y) && is_nan($y)) $state[$k] ^= 2;
    if ($z) $state[$k] ^= 4;
    $state[$k] ^= $w;
}
foreach ($state as $k => $v) {
    if ($v === 123456789) echo $k;
}
echo "ok\n";
?>
--EXPECT--
