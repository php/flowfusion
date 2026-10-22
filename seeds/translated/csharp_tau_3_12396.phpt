--TEST--
Translated seed (inferredbugs-csharp: tau/3)
--FILE--
<?php
$angleRange = 65.0;
$binPerAngle = 1.0;
$hitEvents = [
    ['x' => -32.5],
    ['x' => -32.5],
    ['x' => 0.0],
    ['x' => 32.5],
    ['x' => 32.5],
    ['x' => 32.5],
    ['x' => null],
];

$totalDistributionBins = (int)($angleRange / $binPerAngle) + 1;
$bins = array_fill(0, $totalDistributionBins, 0);

foreach ($hitEvents as $hit) {
    $angle = $hit['x'] ?? 0.0;
    $angle += $angleRange / 2.0;
    $index = (int)round((int)($angle / $binPerAngle), 0, PHP_ROUND_HALF_UP);
    if ($index >= 0 && $index < $totalDistributionBins) {
        $bins[$index]++;
    }
}

$maxCount = max($bins);
$bars = [];
for ($i = 0; $i < $totalDistributionBins; $i++) {
    $bars[] = [
        'index' => $i,
        'height' => max(0.05, $bins[$i] / $maxCount),
    ];
}

$radius = 100.0;
$positions = [];
foreach ($bars as $bar) {
    $angle = ($bar['index'] * $binPerAngle) - ($angleRange / 2.0);
    $rad = deg2rad($angle);
    $x = ($radius - 17.0) * cos($rad);
    $y = ($radius - 17.0) * sin($rad) + $radius;
    $positions[] = [$x, $y, $bar['height'] * 0.15];
}

echo count($positions) . PHP_EOL;
?>
--EXPECT--
