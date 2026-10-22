--TEST--
Translated seed (inferredbugs-csharp: Triangle.NET/1)
--FILE--
<?php
$input = [0, 1, 2, 3];
$mesh = null;
$refineMode = false;
$exceptionThrown = false;
$qualityChecked = true;
$conformDelChecked = true;
$convexChecked = true;
$sweeplineChecked = true;
$minAngle = 20.0;
$maxAngle = 179.5;
$maxArea = 0.25;
$largestArea = 4.0;

if (($input === null && !$refineMode) || $exceptionThrown) {
    exit;
}

if (!$refineMode) {
    $options = [];
    $quality = [];

    if ($conformDelChecked) {
        $options['conforming'] = true;
    }
    if ($qualityChecked) {
        $quality['minAngle'] = $minAngle;
        if ($maxAngle < 180) {
            $quality['maxAngle'] = $maxAngle;
        }
    }
    if ($convexChecked) {
        $options['convex'] = true;
    }

    try {
        if ($sweeplineChecked) {
            $mesh = ['nodes' => $input, 'options' => $options, 'quality' => $quality, 'algo' => 'sweepline'];
        } else {
            $mesh = ['nodes' => $input, 'options' => $options, 'quality' => $quality, 'algo' => 'default'];
        }

        if ($qualityChecked) {
            $refineMode = true;
        }
    } catch (Throwable $ex) {
        $exceptionThrown = true;
    }
} elseif ($qualityChecked) {
    if ($mesh === null) {
        exit;
    }

    $quality = [];
    if ($maxArea > 0 && $maxArea < 1) {
        $quality['maxArea'] = $maxArea * $largestArea;
    }
    $quality['minAngle'] = $minAngle;
    if ($maxAngle < 180) {
        $quality['maxAngle'] = $maxAngle;
    }

    try {
        $mesh['refined'] = true;
        $mesh['quality2'] = $quality;
    } catch (Throwable $ex) {
        $exceptionThrown = true;
    }
}

if ($mesh !== null && !empty($mesh['nodes'])) {
    $mesh['voronoi'] = ($convexChecked ? 'bounded' : 'unbounded');
    $mesh['consistent'] = true;
    $mesh['delaunay'] = true;
}

echo "";
?>
--EXPECT--
