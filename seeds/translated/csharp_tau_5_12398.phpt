--TEST--
Translated seed (inferredbugs-csharp: tau/5)
--FILE--
<?php
$hitEvents = [];
$angleRange = 65.0;
$binPerAngle = 1.0;
$totalDistributionBins = (int)($angleRange / $binPerAngle) + 1;
$bins = array_fill(0, $totalDistributionBins, 0);

for ($i = 0; $i < 1000; $i++) {
    $x = (($i * 37) % 131) - 65;
    $angle = $x + $angleRange / 2;
    $index = (int)round((int)($angle / $binPerAngle), 0, PHP_ROUND_HALF_UP);
    if ($index < 0) $index = 0;
    if ($index >= $totalDistributionBins) $index = $totalDistributionBins - 1;
    $bins[$index]++;
}

$maxCount = max($bins);
$radius = 100.0;
$bars = [];

for ($i = 0; $i < count($bins); $i++) {
    $bars[] = [
        'height' => max(0.075, $bins[$i] / $maxCount) * 0.3,
        'x' => ($radius - 17) * cos(deg2rad(($i * $binPerAngle) - ($angleRange / 2))),
        'y' => $radius + ($radius - 17) * sin(deg2rad(($i * $binPerAngle) - ($angleRange / 2))),
        'index' => $i,
    ];
}

echo count($bars) . PHP_EOL;
?>
--EXPECT--
