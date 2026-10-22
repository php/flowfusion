--TEST--
Translated seed (inferredbugs-csharp: dlr/10)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$f = fopen($path, 'rb');
$data = $f ? stream_get_contents($f) : '';
if ($f) fclose($f);
$len = strlen($data);
$off = $len > 0 ? intdiv($len, 2) : 0;
$chunk = substr($data, $off, 64);
$u = unpack('N*', str_pad($chunk, 64, "\0"));
$s = 0;
foreach ($u as $v) $s ^= $v;
$a = [];
for ($i = 0; $i < 32; $i++) $a[$i] = ($s + $i) & 0xff;
for ($i = 31; $i >= 0; $i--) {
    $j = ($a[$i] + $s + $i) & 31;
    $t = $a[$i];
    $a[$i] = $a[$j];
    $a[$j] = $t;
}
echo pack('C*', ...$a), PHP_EOL;
--EXPECT--
