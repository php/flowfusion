--TEST--
Translated seed (inferredbugs-csharp: rush/4)
--FILE--
<?php
$lane = $argv[1] ?? 'ground';
$isAirLane = $lane === 'air';

$anchor = $isAirLane ? 'TopCentre' : 'BottomCentre';
$paddingLeft = 10;
$hitTargetSize = 40;

$children = [
    [
        'name' => 'Hit Target',
        'anchor' => 'CentreLeft',
        'origin' => 'Centre',
        'size' => [$hitTargetSize, $hitTargetSize],
        'skin' => $isAirLane ? 'AirHitTarget' : 'GroundHitTarget',
    ],
    ['effects' => []],
    ['judgements' => []],
    ['hitObjects' => []],
];

$hitObjects = [
    ['displayExplosion' => true, 'displayResult' => true, 'hit' => true],
    ['displayExplosion' => false, 'displayResult' => true, 'hit' => true],
    ['displayExplosion' => true, 'displayResult' => false, 'hit' => false],
];

foreach ($hitObjects as $hitObject) {
    $result = ['isHit' => $hitObject['hit']];
    if ($result['isHit'] && $hitObject['displayExplosion']) {
        $children[1]['effects'][] = ['type' => 'HitExplosion'];
    }
    if ($hitObject['displayResult']) {
        $children[2]['judgements'][] = [
            'anchor' => 'Centre',
            'origin' => 'Centre',
            'position' => [0.0, -80.0],
            'scale' => [1.5, 1.5],
            'result' => $result,
        ];
    }
}

echo $anchor . "\n";
echo $paddingLeft . "\n";
echo count($children[1]['effects']) . "\n";
echo count($children[2]['judgements']) . "\n";
?>
--EXPECT--
