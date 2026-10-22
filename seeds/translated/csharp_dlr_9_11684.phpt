--TEST--
Translated seed (inferredbugs-csharp: dlr/9)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$f = @fopen($path, 'rb');
if ($f === false) exit(0);
$data = stream_get_contents($f);
fclose($f);
$len = strlen($data);
if ($len === 0) exit(0);
$off = intdiv($len, 3);
$chunk = substr($data, $off, min(64, $len - $off));
$u = unpack('C*', $chunk);
$s = array_sum($u);
$h = hash('sha256', $chunk, true);
$r = substr($h, 0, 8);
$a = array_values(array_unique(str_split(bin2hex($r), 2)));
sort($a);
$g = implode('', $a);
$x = [];
for ($i = 0; $i < strlen($g); $i++) {
    $x[] = ord($g[$i]) ^ ($s & 255);
}
$y = array_map(fn($v) => ($v + $s) & 255, $x);
echo count($y) . "\n";
echo array_sum($y) . "\n";
?>
--EXPECT--
