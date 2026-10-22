--TEST--
Translated seed (inferredbugs-csharp: rush/1)
--FILE--
<?php
$hitTargetOffset = 120.0;
$hitTargetSize = 100.0;
$playerOffset = 130.0;
$judgementOffset = 100.0;
$judgementContainerDrawHeight = 178.0;

$miniBossAttacked = function (float $timeOffset) use ($judgementContainerDrawHeight, $judgementOffset) {
    $scale = (0.9 + mt_rand() / mt_getrandmax() * 0.2) * 1.5;
    $rotation = mt_rand() / mt_getrandmax() * 360.0;
    $pointDifference = (mt_rand(0, 1) ? 1 : -1) * mt_rand(1, 9);
    $lane = mt_rand(0, 1) ? 'Air' : 'Ground';
    $judgementPosition = $lane === 'Air' ? -$judgementOffset : $judgementContainerDrawHeight - $judgementOffset;

    $state = [
        'explosion' => [
            'alpha' => 0,
            'depth' => 0,
            'size' => [200, 200],
            'scale' => $scale,
            'rotation' => $rotation,
        ],
        'healthText' => [
            'text' => sprintf('%+d', $pointDifference),
            'colour' => $pointDifference > 0 ? 'Green' : 'Red',
            'position' => [0.75, 0.5],
        ],
        'judgement' => [
            'lane' => $lane,
            'position' => $judgementPosition,
            'scale' => $lane === 'Air' ? 1.2 : 1.5,
        ],
        'playerTarget' => 'MiniBoss',
    ];

    json_encode($state);
};

for ($i = 0; $i < 3; $i++) {
    $miniBossAttacked($i * 10.0);
}
?>
--EXPECT--
