--TEST--
Translated seed (inferredbugs-csharp: tau/4)
--FILE--
<?php
$circleSize = 5.0;
$angleRange = max(10.0, min(75.0, 75.0 - ($circleSize - 5.0) * (65.0 / 5.0)));
$hitEvents = [
    ['hitWindows' => true, 'type' => 'Beat', 'hit' => true, 'x' => -$angleRange / 2],
    ['hitWindows' => true, 'type' => 'Beat', 'hit' => true, 'x' => 0],
    ['hitWindows' => true, 'type' => 'Beat', 'hit' => true, 'x' => $angleRange / 2],
    ['hitWindows' => false, 'type' => 'Beat', 'hit' => true, 'x' => 999],
    ['hitWindows' => true, 'type' => 'Slider', 'hit' => true, 'x' => 123],
    ['hitWindows' => true, 'type' => 'Beat', 'hit' => false, 'x' => 456],
];

$filtered = [];
foreach ($hitEvents as $e) {
    if (!$e['hitWindows'] && $e['type'] === 'Beat' && $e['hit'])
        $filtered[] = $e;
    elseif ($e['hitWindows'] && $e['type'] === 'Beat' && $e['hit'])
        $filtered[] = $e;
}

$binPerAngle = 1.0;
$totalDistributionBins = (int)($angleRange / $binPerAngle) + 1;
$bins = array_fill(0, $totalDistributionBins, 0);

foreach ($filtered as $hit) {
    $angle = ($hit['x'] ?? 0.0) + $angleRange / 2.0;
    $index = (int)round((int)($angle / $binPerAngle), 0, PHP_ROUND_HALF_UP);
    if ($index >= 0 && $index < $totalDistributionBins)
        $bins[$index]++;
}

$maxCount = max($bins ?: [0]);
$bars = [];
for ($i = 0; $i < $totalDistributionBins; $i++) {
    $bars[] = [
        'index' => $i,
        'height' => max(0.075, $maxCount > 0 ? $bins[$i] / $maxCount : 0) * 0.3,
    ];
}

$radius = 100.0;
$positions = [];
foreach ($bars as $bar) {
    $angle = ($bar['index'] * $binPerAngle) - ($angleRange / 2.0);
    $rad = deg2rad($angle);
    $x = ($radius - 17.0) * cos($rad);
    $y = ($radius - 17.0) * sin($rad) + $radius;
    $positions[] = [$x, $y, $bar['height']];
}

echo count($positions), PHP_EOL;
?>
--EXPECT--
