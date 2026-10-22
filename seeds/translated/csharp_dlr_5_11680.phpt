--TEST--
Translated seed (inferredbugs-csharp: dlr/5)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$f = @fopen($path, 'rb');
if ($f === false) {
    exit(0);
}
$data = stream_get_contents($f);
fclose($f);
$len = strlen($data);
$off = 0;
$n = $len > 0 ? ord($data[$off++]) : 0;
$rows = [];
for ($i = 0; $i < $n && $off < $len; $i++) {
    $rows[] = ord($data[$off++]);
}
$sum = 0;
foreach ($rows as $r) {
    $sum += $r;
}
$idx = $n > 0 ? $sum % $n : 0;
$view = array_slice($rows, 0, $idx);
$count = count($view);
if ($count !== $idx) {
    throw new Exception('count mismatch');
}
$map = [];
for ($i = 0; $i < $n; $i++) {
    $map[$i] = $rows[$i] ?? 0;
}
ksort($map);
$last = null;
foreach ($map as $k => $v) {
    $last = $k ^ $v;
}
echo $count . ":" . ($last ?? 0) . PHP_EOL;
--EXPECT--
