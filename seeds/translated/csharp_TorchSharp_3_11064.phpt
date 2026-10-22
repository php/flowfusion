--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/3)
--FILE--
<?php
$s = range(1, 6);
$a = array_chunk($s, 2);
$b = array_merge(...$a);
$c = array_slice($b, 1, 4);
$d = array_map(fn($x) => $x + 1, $c);
$e = array_reduce($d, fn($carry, $x) => $carry + $x, 0);
$f = str_repeat(chr($e % 256), 3);
$g = unpack('C*', $f);
$h = array_values($g);
$i = array_chunk($h, 2);
$j = array_map(fn($x) => array_sum($x), $i);
$k = implode(',', $j);
echo $k, PHP_EOL;
?>
--EXPECT--
