--TEST--
Translated seed (inferredbugs-csharp: dlr/6)
--FILE--
<?php
$path = __FILE__;
$f = fopen($path, 'rb');
$data = stream_get_contents($f);
fclose($f);
$len = strlen($data);
$off = $len > 0 ? intdiv($len, 3) : 0;
$chunk = substr($data, $off, $len > 0 ? min($len - $off, 1024) : 0);
$h = hash('sha256', $chunk, true);
$a = unpack('N4', substr($h, 0, 16));
$b = unpack('N4', substr($h, 16, 16));
$x = ($a[1] ^ $b[2]) + ($a[3] & $b[4]);
$y = ($a[2] + $b[1]) ^ ($a[4] | $b[3]);
$s = [];
for ($i = 0; $i < 24; $i++) {
    $s[] = ($x + $y + $i) & 255;
}
for ($i = 0; $i < 8; $i++) {
    $idx = ($s[$i] + $s[23 - $i]) % 24;
    $s[$idx] = ($s[$idx] ^ (($x >> ($i & 7)) & 255)) & 255;
}
$tokens = [];
for ($i = 0; $i < 12; $i++) {
    $tokens[] = (($s[$i] << 24) | ($s[$i + 1] << 16) | ($s[$i + 2] << 8) | $s[$i + 3]) & 0x7fffffff;
}
$views = [];
for ($i = 0; $i < 6; $i++) {
    $start = $tokens[$i] % 12;
    $count = ($tokens[$i + 6] % (12 - $start)) + 1;
    $views[] = array_slice($tokens, $start, $count);
}
foreach ($views as $v) {
    sort($v);
    $r = array_reduce($v, fn($c, $n) => (($c << 1) ^ $n) & 0x7fffffff, 0);
    $m = [];
    foreach ($v as $n) {
        $m[$n & 7] = ($m[$n & 7] ?? 0) + 1;
    }
    ksort($m);
    $r ^= count($m) + array_sum($m);
}
echo "";
?>
--EXPECT--
