--TEST--
Translated seed (inferredbugs-csharp: neolua/3)
--FILE--
<?php
$g = [];
$a = [];
for ($i = 0; $i < 20000; $i++) {
    $k = ($i & 1) ? "k$i" : $i;
    $a[$k] = $i;
    if (($i & 255) === 0) {
        $b = [$a, $g];
        $g = $b[0];
        $x = array_key_exists($k, $g) ? $g[$k] : null;
        $y = $x ?? 0;
        $z = ($y + 1) << 1;
        $g[$k] = $z ^ ($i & 7);
    }
}
$h = [];
foreach ($g as $k => $v) {
    $h[(string)$k] = is_int($v) ? $v : 0;
}
echo count($h) . "\n";
?>
--EXPECT--
