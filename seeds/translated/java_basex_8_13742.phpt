--TEST--
Translated seed (inferredbugs-java: basex/8)
--FILE--
<?php

$pointerx = 0;
$pointery = 0;
$fontHeight = 0;
$margin = 3;
$lvdistance = 30;
$topdistance = 20;
$prePostMulti = 17;
$sumNodeSizeInLine = 0;
$parentList = [];
$parentPos = [];
$rects = [];
$rectCount = 0;
$mousePosX = -1;
$mousePosY = -1;

function main() {
    global $pointerx, $pointery, $fontHeight, $topdistance;
    
    $pointerx = 100; // Arbitrary width
    $pointery = $topdistance;

    paintComponent();
}

function paintComponent() {
    global $pointerx, $pointery, $fontHeight;
    $fontHeight = 10; // Arbitrary font height

    $sumNodeSizeInLine = 100; // Arbitrary total size
    $parentList = [0];
    $rectCount = 1;
    $parentPos = [];

    $level = 0;
    while (count($parentList) > 0) {
        drawTemperature($level);
        getNextNodeLine();
        $level++;
    }
}

function drawTemperature($level) {
    global $pointerx, $fontHeight, $rectCount, $parentList, $sumNodeSizeInLine, $rects;

    $tRect = array_fill(0, $rectCount, null);
    $y = 1 * $level * $fontHeight * 2;
    $width = 200 - 1;
    $ratio = $width / count($parentList);
    
    $x = 0;
    $r = 0;

    foreach ($parentList as $pre) {
        if ($pre == -1) {
            $x += $ratio;
            continue;
        }

        $nodeSize = 10; // Simulate node size
        $nodePercent = $nodeSize / $sumNodeSizeInLine;
        $l = (int)$ratio;

        $tRect[$r++] = [$pre, (int)$x, $y, (int)$x + $l, $y + $fontHeight];
        $c = min((int)round(255 * $nodePercent * 40), 255);
        
        $x += $ratio;
    }
    
    $rects[] = $tRect;
}

function getNextNodeLine() {
    global $parentList;
    $temp = [];
    $sumNodeSize = 0;
    $rCount = 0;

    foreach ($parentList as $p) {
        if ($p == -1) continue;

        $temp[] = $p + 1; // Simulating child nodes
        $rCount++;
        $sumNodeSize += 10; // Simulating size
    }

    $parentList = $temp;
}

main();
--EXPECT--
