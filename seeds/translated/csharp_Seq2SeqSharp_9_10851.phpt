--TEST--
Translated seed (inferredbugs-csharp: Seq2SeqSharp/9)
--FILE--
<?php
$deviceIds = [0, 1, 2];
$i = 0;
$n = count($deviceIds);
for ($k = 0; $k < 12; $k++) {
    $item = $deviceIds[$i];
    $i = ($i + 1) % $n;
    $x = [$item, $deviceIds[$i], $deviceIds[($i + 1) % $n]];
    $y = array_map(fn($v) => ($v + 1) % 3, $x);
    $z = array_reduce($y, fn($c, $v) => $c + $v, 0);
    if (($z & 1) === 0) {
        $deviceIds = array_reverse($deviceIds);
        $n = count($deviceIds);
        $i %= $n;
    }
}
echo "ok\n";
?>
--EXPECT--
