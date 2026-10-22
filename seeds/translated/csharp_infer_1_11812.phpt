--TEST--
Translated seed (inferredbugs-csharp: infer/1)
--FILE--
<?php
$w1 = [1 => 1.0, 2 => 2.0];
$w2 = [2 => 2.0, 1 => 1.0];
$k1 = implode(',', array_map(fn($k, $v) => $k . ':' . $v, array_keys($w1), $w1));
$k2 = implode(',', array_map(fn($k, $v) => $k . ':' . $v, array_keys($w2), $w2));
$a = [$k1 => true];
$a[$k2] = true;
foreach ($a as $k => $v) {
    echo $k, "\n";
}
?>
--EXPECT--
