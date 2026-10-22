--TEST--
Translated seed (inferredbugs-csharp: cscs/96)
--FILE--
<?php
$nan = NAN;
$inf = INF;
$ninf = -INF;
$vals = [$nan, $inf, $ninf, 0, 1, "1", "1.5", "foo", null, true, false];
foreach ($vals as $v) {
    $isnan = is_float($v) && is_nan($v);
    $isfinite = is_numeric($v) ? is_finite($v + 0) : false;
    $type = gettype($v);
    $s = (string)$v;
    $i = (int)$s;
    $d = (float)$s;
    $b = filter_var($s, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $x = [$v];
    $x[] = $v;
    $x[2] = $v;
    $y = $x;
    $y[0] = $i;
    $z = array_merge($x, $y);
    $k = array_key_exists(2, $x);
    $p = in_array($v, $z, true);
    $m = array_search($v, $z, true);
    $o = (object)["a" => $v];
    $o->b = $x;
    $o->c = $o;
    $r = json_encode($o);
    $u = json_decode($r, true);
}
function f($a = 1, $b = 2, $c = 3) {
    return [$a, $b, $c];
}
f(c: 3, a: 1);
try {
    throw new Exception((string)$nan);
} catch (Throwable $e) {
}
echo "ok\n";
?>
--EXPECT--
