--TEST--
Translated seed (inferredbugs-csharp: P/18)
--FILE--
<?php
$max = PHP_INT_MAX;
$a = [$max, $max - 1, 0, -1];
$b = array_map(fn($x) => $x + 1, $a);
$c = [];
foreach ($b as $k => $v) {
    $c[$k] = (string)$v;
}
$d = array_fill_keys($c, true);
$e = [];
foreach ($d as $k => $v) {
    $e[] = $k;
}
sort($e);
echo implode(",", $e), "\n";
?>
--EXPECT--
