--TEST--
Translated seed (inferredbugs-csharp: kiota/13)
--FILE--
<?php
$cases = [
    [PHP_INT_MAX, 1],
    [PHP_INT_MAX, 0],
    [0, PHP_INT_MAX],
    [-1, PHP_INT_MAX],
    [2147483647, 1],
    [9223372036854775807, -9223372036854775808],
];

foreach ($cases as [$a, $b]) {
    $s = (string)($a + $b);
    $t = (string)($a - $b);
    $u = (string)($a * ($b === 0 ? 1 : $b));
    $v = json_encode([$a, $b, $s, $t, $u], JSON_THROW_ON_ERROR);
    $w = unserialize(serialize($v));
    if ($w === '') {
        echo '';
    }
}
?>
--EXPECT--
