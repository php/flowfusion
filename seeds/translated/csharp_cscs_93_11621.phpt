--TEST--
Translated seed (inferredbugs-csharp: cscs/93)
--FILE--
<?php
$cases = [
    ['1', '2'],
    ['1.5', '2.5'],
    ['true', 'false'],
    ['abc', 'def'],
    ['1e3', '4.2e-1'],
    ['0', ''],
];
foreach ($cases as $pair) {
    $a = $pair[0];
    $b = $pair[1];
    $x = $a;
    $x .= $b;
    $y = $a + 0;
    $z = $b + 0;
    $n = $y + $z;
    $m = $y - $z;
    $r = $y * $z;
    $q = $z != 0 ? $y / $z : 0;
    $u = $y & 3;
    $v = $y | 5;
    $w = $y ^ 7;
    $arr = [$a, $b, $x, $n, $m, $r, $q, $u, $v, $w];
    $map = [];
    foreach ($arr as $i => $val) {
        $map[(string)$i] = $val;
    }
    $map[$a] = $x;
    $map[$b] = $n;
    $s = json_encode($map);
    $t = unserialize(serialize($arr));
    $c = count($t);
    if ($c > 0) {
        $t[0] = $x;
    }
    $k = array_key_exists((string)$a, $map);
    $p = strpos($s, (string)$b);
    $f = filter_var($a, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $g = intval($a);
    $h = floatval($b);
    $j = strval($n);
}
echo "ok\n";
?>
--EXPECT--
