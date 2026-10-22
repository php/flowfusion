--TEST--
Translated seed (inferredbugs-csharp: brightwire/3)
--FILE--
<?php
$path = $argv[1] ?? '';
if ($path === '' || !is_file($path)) {
    $path = 'php://stdin';
}
$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    exit(0);
}

$data = [];
foreach ($lines as $line) {
    $row = array_map('trim', explode(',', trim($line)));
    if ($row === ['']) continue;
    $data[] = array_map('floatval', $row);
}
if (!$data) exit(0);

$n = count($data);
$m = count($data[0]);
$sum = array_fill(0, $m, 0.0);
$sum2 = array_fill(0, $m, 0.0);

foreach ($data as $row) {
    for ($j = 0; $j < $m; $j++) {
        $x = $row[$j];
        $sum[$j] += $x;
        $sum2[$j] += $x * $x;
    }
}

$mean = $var = array_fill(0, $m, 0.0);
for ($j = 0; $j < $m; $j++) {
    $mean[$j] = $sum[$j] / $n;
    $var[$j] = max(0.0, $sum2[$j] / $n - $mean[$j] * $mean[$j]);
    $var[$j] = sqrt($var[$j]);
    if ($var[$j] == 0.0) $var[$j] = 1.0;
}

$alpha = 1.6732632423543772;
$scale = 1.0507009873554805;

$out = [];
foreach ($data as $row) {
    $norm = [];
    for ($j = 0; $j < $m; $j++) {
        $x = ($row[$j] - $mean[$j]) / $var[$j];
        $y = ($x >= 0.0) ? $scale * $x : $scale * ($alpha * exp($x) - $alpha);
        $g = ($y >= 0.0) ? $scale : ($scale * $alpha * exp($y));
        $norm[] = $y + $g * 0.0;
    }
    $out[] = implode(',', array_map(static fn($v) => sprintf('%.10f', $v), $norm));
}

echo implode(PHP_EOL, $out), PHP_EOL;
--EXPECT--
