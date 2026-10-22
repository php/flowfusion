--TEST--
Translated seed (inferredbugs-java: commons-imaging/41)
--FILE--
<?php

$bitsPerPixel = 24;
$width = 100;
$height = 100;
$rowData = array_fill(0, $width * $height * ($bitsPerPixel / 8), 0);

// Simulating the row data for an image
for ($i = 0; $i < count($rowData); $i += 3) {
    $rowData[$i] = rand(0, 255);     // Red
    $rowData[$i + 1] = rand(0, 255); // Green
    $rowData[$i + 2] = rand(0, 255); // Blue
}

$imageBuilder = [];

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $k = ($y * $width + $x) * 3;
        $rgb = 0xff000000 | ($rowData[$k] << 16) | ($rowData[$k + 1] << 8) | $rowData[$k + 2];
        $imageBuilder[$y][$x] = $rgb;
    }
}

exit(0);
--EXPECT--
