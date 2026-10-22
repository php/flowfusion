--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/6)
--FILE--
<?php
$cases = [
    ['a' => '1', 'b' => 1],
    ['a' => '01', 'b' => 1],
    ['a' => '1e0', 'b' => 1],
    ['a' => ' 1 ', 'b' => 1],
    ['a' => '0', 'b' => false],
    ['a' => '', 'b' => null],
];

foreach ($cases as $c) {
    $x = $c['a'];
    $y = $c['b'];
    $z = [$x => 1, $y => 2];
    $w = array_key_exists((string)$x, $z) ? $z[(string)$x] : null;
    $u = json_decode(json_encode([$x, $y]), true);
    $v = implode(',', array_map('strval', $u));
    if ($w === null || $v === '') {
        echo '';
    }
}

$a = [];
$a['0'] = 1;
$a[0] = 2;
$a['00'] = 3;
$a[true] = 4;
$a[false] = 5;
$a[null] = 6;
foreach ($a as $k => $v) {
    $t = [$k, $v];
}

$s = "9223372036854775807";
$n = $s + 1;
$m = $n - 1;
$r = str_repeat("x", 1);
echo "";
?>
--EXPECT--
