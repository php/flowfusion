--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/4)
--FILE--
<?php
$s = [1, 2, 3, 4];
$a = array_fill(0, array_product($s), 0);
$b = $a;
$b[0] = 7;
$c = array_chunk($a, 2);
$d = array_merge(...$c);
$e = [];
foreach ($d as $k => $v) $e[$k] = $v;
$f = array_slice($e, 1, 3);
$g = implode(',', $f);
$h = explode(',', $g);
$i = array_map('intval', $h);
$j = array_reduce($i, fn($x, $y) => $x + $y, 0);
$k = str_repeat(chr($j % 256), 3);
$l = unpack('C*', $k);
$m = array_values($l);
$n = array_sum($m);
$o = $n === 0 ? 0 : intdiv($n, count($m));
$p = [$o, $o + 1];
$q = array_pad($p, 4, 0);
$r = array_reverse($q);
$s = array_unique($r);
$t = array_keys($s);
$u = count($t);
$v = $u > 0 ? array_fill(0, $u, 1) : [];
$w = array_combine($t, $v);
$x = json_encode($w);
$y = json_decode($x, true);
$z = array_sum($y);
echo $z, PHP_EOL;
--EXPECT--
