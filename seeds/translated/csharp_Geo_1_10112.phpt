--TEST--
Translated seed (inferredbugs-csharp: Geo/1)
--FILE--
<?php

$fixes = [
    ['coordinate' => [0.0, 0.0], 'timeUtc' => 0.0],
    ['coordinate' => [1.0, 1.0], 'timeUtc' => 0.4],
    ['coordinate' => [2.0, 2.0], 'timeUtc' => 1.1],
    ['coordinate' => [3.0, 3.0], 'timeUtc' => 1.9],
    ['coordinate' => [4.0, 4.0], 'timeUtc' => 3.0],
];

$quantized = [];
$lastFix = null;
$seconds = 1.0;

foreach ($fixes as $fix) {
    if ($lastFix === null || abs($fix['timeUtc'] - $lastFix['timeUtc']) >= $seconds) {
        $lastFix = $fix;
        $quantized[] = $fix;
    }
}

$first = $quantized[0] ?? null;
$last = $quantized[count($quantized) - 1] ?? null;

$duration = ($last['timeUtc'] ?? 0.0) - ($first['timeUtc'] ?? 0.0);

$length = 0.0;
for ($i = 1, $n = count($quantized); $i < $n; $i++) {
    $dx = $quantized[$i]['coordinate'][0] - $quantized[$i - 1]['coordinate'][0];
    $dy = $quantized[$i]['coordinate'][1] - $quantized[$i - 1]['coordinate'][1];
    $length += sqrt($dx * $dx + $dy * $dy);
}

$averageSpeed = $duration != 0.0 ? $length / $duration : 0.0;

echo $length . "\n";
echo $duration . "\n";
echo $averageSpeed . "\n";
echo count($quantized) . "\n";
--EXPECT--
