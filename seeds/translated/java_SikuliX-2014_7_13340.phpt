--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/7)
--FILE--
<?php

$minDarkerFactor = 0.6;
$dragging = false;
$canceled = false;
$srcx = $srcy = $destx = $desty = 0;
$rectSelection = ['x' => 0, 'y' => 0, 'width' => 0, 'height' => 0];

function simulateMouseEvents($mousePressX, $mousePressY, $mouseReleaseX, $mouseReleaseY)
{
    global $srcx, $srcy, $destx, $desty, $dragging, $canceled, $rectSelection;

    $srcx = $destx = $mousePressX;
    $srcy = $desty = $mousePressY;
    $dragging = true;

    $destx = $mouseReleaseX;
    $desty = $mouseReleaseY;
    
    if ($srcx !== $destx || $srcy !== $desty) {
        $rectSelection['x'] = min($srcx, $destx);
        $rectSelection['y'] = min($srcy, $desty);
        $rectSelection['width'] = abs($destx - $srcx);
        $rectSelection['height'] = abs($desty - $srcy);
    }
}

function drawSelection()
{
    global $rectSelection;
    
    if ($rectSelection['width'] > 0 && $rectSelection['height'] > 0) {
        echo "Drawing selection rectangle at (" . $rectSelection['x'] . ", " . $rectSelection['y'] . ") with width " . $rectSelection['width'] . " and height " . $rectSelection['height'] . "\n";
    }
}

simulateMouseEvents(10, 10, 100, 100);
drawSelection();

$canceled = true;
--EXPECT--
