--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/2)
--FILE--
<?php
$bodyList = [];
$active = [];
$swapOrder = false;

$makeBody = function ($minX, $maxX, $minY, $maxY, $minZ, $maxZ, $static) {
    return [
        'bbox' => ['min' => ['x' => $minX, 'y' => $minY, 'z' => $minZ], 'max' => ['x' => $maxX, 'y' => $maxY, 'z' => $maxZ]],
        'static' => $static
    ];
};

$bodyList[] = $makeBody(5, 6, 0, 1, 0, 1, false);
$bodyList[] = $makeBody(1, 2, 0, 1, 0, 1, true);
$bodyList[] = $makeBody(3, 4, 0, 1, 0, 1, false);
$bodyList[] = $makeBody(2, 3, 0, 1, 0, 1, true);
$bodyList[] = $makeBody(4, 5, 0, 1, 0, 1, false);

usort($bodyList, function ($a, $b) {
    $f = $a['bbox']['min']['x'] - $b['bbox']['min']['x'];
    return ($f < 0) ? -1 : (($f > 0) ? 1 : 0);
});

foreach ($bodyList as $body) {
    $xmin = $body['bbox']['min']['x'];
    $thisInactive = $body['static'];
    for ($i = 0; $i !== count($active); ) {
        $ac = $active[$i];
        $acBox = $ac['bbox'];
        if ($acBox['max']['x'] < $xmin) {
            array_splice($active, $i, 1);
        } else {
            $bodyBox = $body['bbox'];
            if (!($thisInactive && $ac['static']) &&
                $bodyBox['max']['z'] >= $acBox['min']['z'] && $bodyBox['min']['z'] <= $acBox['max']['z'] &&
                $bodyBox['max']['y'] >= $acBox['min']['y'] && $bodyBox['min']['y'] <= $acBox['max']['y']) {
                $swapOrder = !$swapOrder;
            }
            $i++;
        }
    }
    $active[] = $body;
}

echo count($active), PHP_EOL;
--EXPECT--
