--TEST--
Translated seed (inferredbugs-csharp: dlr/7)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$f = fopen($path, 'rb');
if ($f === false) exit(0);
$data = stream_get_contents($f);
fclose($f);
$len = strlen($data);
$off = $len > 0 ? intdiv($len, 2) : 0;
$chunk = substr($data, $off, $len > 0 ? min(64, $len - $off) : 0);
$bytes = array_values(unpack('C*', $chunk));
$sum = 0;
foreach ($bytes as $b) $sum += $b;
$map = [];
for ($i = 0; $i < count($bytes); $i++) $map[$i + 1] = $bytes[$i];
$keys = array_keys($map);
sort($keys);
$out = '';
foreach ($keys as $k) $out .= chr($map[$k] ^ ($sum & 255));
echo $out;
?>
--EXPECT--
