--TEST--
Translated seed (inferredbugs-java: commons-imaging/42)
--FILE--
<?php

$tileWidth = 100;
$tileLength = 100;
$width = 500;
$height = 500;
$bitsPerPixel = 24;

$tileData = str_repeat("\0", $tileWidth * $tileLength * ($bitsPerPixel / 8));

$xLimit = $width;
$yLimit = $height;

$startX = 0;
$startY = 0;

for ($i = $startY; $i < $startY + $tileLength; $i++) {
    if ($i >= $yLimit) break;
    for ($j = $startX; $j < $startX + $tileWidth; $j++) {
        if ($j >= $xLimit) break;
        $k = (($i - $startY) * $tileWidth + ($j - $startX)) * 3;
        $bytes = unpack("C*", substr($tileData, $k, 3));
        $rgb = 0xff000000 | (($bytes[1] << 16) | ($bytes[2] << 8) | $bytes[3]);
        // Simulated image setting (no actual image processing)
    }
}
--EXPECT--
